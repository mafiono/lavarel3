<?php

namespace App\Bonus;

use App\Bonus;
use App\Lib\Mail\SendMail;
use App\User;
use App\UserBonus;
use App\UserTransaction;
use Auth;
use Carbon\Carbon;
use DB;
use Lang;

abstract class BaseBonus
{
    protected $user;

    protected $userBonus;

    protected $origin;

    public function __construct(User $user = null, UserBonus $userBonus = null)
    {
        $this->user = $user ?? Auth::user();

        $this->userBonus = $userBonus
            ?? ($this->user ? $this->getActive() : null);
    }

    public static function make(User $user = null, UserBonus $userBonus = null)
    {
        $user = $user ? $user : Auth::user();

        if (is_null($user)) {
            return static::noBonus();
        }

        $activeBonus = $userBonus ?: static::activeUserBonus($user->id);

        if (is_null($activeBonus)) {
            return static::noBonus($user);
        }

        return static::bonus($user, $activeBonus);
    }

    protected static function activeUserBonus($userId, $origin = null)
    {
        return UserBonus::activeFromUser($userId, ['bonus'])
            ->origin($origin)
            ->first();
    }

    abstract protected static function bonus(User $user, UserBonus $userBonus);

    abstract protected static function noBonus(User $user = null);

    abstract public function getAvailable($columns = ['*']);

    abstract public function isAvailable($bonusId);

    public function getActive($columns = ['*'])
    {
        return UserBonus::fromUser($this->user->id)
            ->active()
            ->origin($this->origin)
            ->first($columns);
    }

    public function hasActive()
    {
        return UserBonus::fromUser($this->user->id)
                ->active()
                ->origin($this->origin)
                ->exists();
    }

    public function getConsumed($columns = ['*'])
    {
        return UserBonus::fromUser($this->user->id)
            ->consumed()
            ->origin($this->origin)
            ->get($columns);
    }

    public function hasId($bonusId)
    {
        return $this->userBonus->id == $bonusId;
    }

    public function redeem($bonusId)
    {
        $this->selfExcludedCheck();

        if (!$this->isAvailable($bonusId) || $this->hasActive()) {
            $this->throwException(Lang::get('bonus.redeem.error'));
        }

        DB::transaction(function () use ($bonusId) {
            $bonus = Bonus::findOrFail($bonusId);

            $userSession = $this->user->logUserSession('bonus.redeem', 'Redeem Bonus: '. $bonus->title);

            $deposit = UserTransaction::latestUserDeposit($this->user->id)
                ->first();

            $userBonus = UserBonus::create([
                'user_id' => $this->user->id,
                'user_session_id' => $userSession->id,
                'bonus_id' => $bonusId,
                'bonus_head_id' => $bonus->head_id,
                'user_transaction_id' => $deposit->id ?? null,
                'deadline_date' => Carbon::now()->addDay($bonus->deadline),
                'active' => 1,
            ]);

            $this->redeemedEvent($userBonus);

            $mail = new SendMail(SendMail::$TYPE_8_BONUS_ACTIVATED);
            $mail->prepareMail($this->user, [
                'title' => 'Bónus',
                'url' => '/promocoes',
            ], $userSession->id);
            $mail->Send(false);
        });
    }

    public function deposit()
    {
        $bonusAmount = $this->redeemAmount();

        $initial_bonus = $this->user->balance->balance_bonus;
        $this->user->balance->addBonus($bonusAmount);

        $this->userBonus->update([
            'bonus_value' => $bonusAmount,
            'rollover_amount' => $bonusAmount * $this->userBonus->bonus->rollover_coefficient,
            'deposited' => $this->deposited(),
        ]);

        UserTransaction::forceCreate([
            'user_id' => $this->user->id,
            'origin' => $this->origin . '_bonus',
            'transaction_id' => UserTransaction::getHash($this->user->id, Carbon::now()),
            'debit_bonus' => $bonusAmount,
            'initial_balance' => $this->user->balance->balance_available,
            'initial_bonus' => $initial_bonus,
            'final_balance' => $this->user->balance->balance_available,
            'final_bonus' => $this->user->balance->balance_bonus,
            'date' => Carbon::now(),
            'description' => 'Resgate de bónus ' . $this->userBonus->bonus->title,
            'status_id' => 'processed',
        ]);
    }

    public function isCancellable()
    {
        return false;
    }

    public function cancel()
    {
        $this->selfExcludedCheck();

        if (!$this->isCancellable()) {
            $this->throwException(Lang::get('bonus.cancel.error'));
        }

        $this->deactivate();
    }

    abstract public function previewRedeemAmount(Bonus $bonus = null);

    public function redeemAmount(Bonus $bonus = null)
    {
        $deposit = UserTransaction::latestUserDeposit($this->user->id)
            ->first();

        $bonus = $this->userBonus->bonus ?? $bonus;

        return min(
            $deposit->debit * $bonus->value * 0.01,
            $bonus->max_bonus
        );
    }

    public function refreshUser()
    {
        $this->user = $this->user->fresh();
    }

    public function userBonus()
    {
        return $this->userBonus;
    }

    public function forceCancel()
    {
        $this->deactivate();
    }

    protected function deactivate()
    {
        DB::transaction(function () {
            $balance = $this->user->balance->fresh();
            $bonusAmount = $balance->balance_bonus*1;

            $this->userBonus->active = 0;
            $this->userBonus->balance_bonus = $bonusAmount;
            $this->userBonus->save();

            if ($bonusAmount) {
                $balance->subtractBonus($bonusAmount);
            }

            UserTransaction::forceCreate([
                'user_id' => $this->user->id,
                'origin' => $this->origin.'_bonus',
                'transaction_id' => UserTransaction::getHash($this->user->id, Carbon::now()),
                'credit_bonus' => $bonusAmount,
                'initial_balance' => $balance->balance_available,
                'initial_bonus' => $bonusAmount,
                'final_balance' => $balance->balance_available,
                'final_bonus' => $balance->balance_bonus,
                'date' => Carbon::now(),
                'description' => 'Término de bónus ' . $this->userBonus->bonus->title,
                'status_id' => 'processed',
            ]);
        });

        $this->canceledEvent($this->userBonus);
    }

    protected function selfExcludedCheck()
    {
        if ($this->user->isSelfExcluded()) {
            $this->throwException(Lang::get('bonus.self_excluded.error'));
        }
    }

    protected function deposited()
    {
        return true;
    }

    abstract protected function throwException($message = null);

    abstract protected function canceledEvent(UserBonus $userBonus);

    abstract protected function redeemedEvent(UserBonus $userBonus);
}
