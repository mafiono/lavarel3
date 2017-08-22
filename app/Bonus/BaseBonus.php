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
            ?? $this->user ? $this->getActive() : null;
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

    public function redeem($bonusId)
    {
        $this->selfExcludedCheck();

        if (!$this->isAvailable($bonusId) || $this->hasActive()) {
            $this->throwException(Lang::get('bonus.redeem.error'));
        }

        DB::transaction(function () use ($bonusId) {
            $bonus = Bonus::findOrFail($bonusId);

            $userSession = $this->user->logUserSession('bonus.redeem', 'Redeem Bonus: '. $bonus->title);

            $userBonus = UserBonus::create([
                'user_id' => $this->user->id,
                'user_session_id' => $userSession->id,
                'bonus_id' => $bonusId,
                'bonus_head_id' => $bonus->head_id,
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

    abstract public function isCancellable();

    public function cancel()
    {
        $this->selfExcludedCheck();

        if (!$this->isCancellable()) {
            $this->throwException(Lang::get('bonus.cancel.error'));
        }

        $this->deactivate();
    }

    protected function deactivate()
    {
        DB::transaction(function () {
            $this->userBonus->active = 0;
            $this->userBonus->save();

            $balance = $this->user->balance->fresh();
            $bonusAmount = $balance->balance_bonus*1;

            if ($bonusAmount) {
                $balance->subtractBonus($bonusAmount);
            }

            UserTransaction::forceCreate([
                'user_id' => $this->user->id,
                'origin' => 'sport_bonus',
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

    abstract protected function throwException($message = null);

    abstract protected function canceledEvent(UserBonus $userBonus);

    abstract protected function redeemedEvent(UserBonus $userBonus);

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
}
