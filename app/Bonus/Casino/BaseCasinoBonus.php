<?php

namespace App\Bonus\Casino;


use App\Bonus;
use App\Bonus\BaseBonus;
use App\Bonus\Sports\FirstBet;
use App\Bonus\Sports\FirstDeposit;
use App\Bonus\Sports\NoBonus;
use App\User;
use App\UserBonus;
use Auth;

abstract class BaseCasinoBonus extends BaseBonus
{
    protected $origin = 'casino';

    protected static function bonus(User $user, UserBonus $userBonus)
    {
        switch (($userBonus->bonus->bonus_type_id)) {
            case 'casino_deposit':
                return new Deposit($user, $userBonus);
        }

        return static::noBonus($user);
    }

    protected static function noBonus(User $user = null)
    {
        return new NoBonus($user);
    }

    public function swapBonus(UserBonus $bonus = null)
    {
        app()->instance('sports.bonus', self::make($this->user, $bonus));

        self::swap(app()->make('sports.bonus'));
    }

    public function swapUser(User $user, UserBonus $bonus = null)
    {
        app()->instance('sports.bonus', self::make($user, $bonus));

        self::swap(app()->make('sports.bonus'));
    }

    public function getAvailable($columns = ['*'])
    {
        $redeemed = UserBonus::whereUserId($this->user)->get();

        return Bonus::whereOrigin($this->origin)
            ->currents()
            ->availableBetweenNow()
            ->get()
            ->filter(function ($bonus) use ($redeemed) {
                return !$redeemed->contains('head_id', $bonus->head_id);
            });
    }

    public function getActive($columns = ['*'])
    {
        return UserBonus::fromUser($this->user->id)
            ->active()
            ->origin($this->origin)
            ->first($columns);
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
            throw new SportsBonusException(Lang::get('bonus.redeem.error'));
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

            SportsBonus::swapBonus($userBonus);

            SportsBonus::deposit();

            $mail = new SendMail(SendMail::$TYPE_8_BONUS_ACTIVATED);
            $mail->prepareMail($this->user, [
                'title' => 'Bónus',
                'url' => '/promocoes',
            ], $userSession->id);
            $mail->Send(false);
        });
    }

    public function cancel()
    {
        $this->selfExcludedCheck();

        if (!$this->isCancellable()) {
            throw new SportsBonusException(Lang::get('bonus.cancel.error'));
        }

        $this->deactivate();
    }

    protected function deactivate()
    {

    }

    protected function selfExcludedCheck()
    {
        if ($this->user->isSelfExcluded()) {
            throw new SportsBonusException(Lang::get('bonus.self_excluded.error'));
        }
    }

}