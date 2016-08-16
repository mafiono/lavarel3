<?php

namespace App\Bonus;


use App\Bonus;
use App\User;
use App\UserBonus;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;

class SportsBonus
{
    protected $user;

    public function __construct(User $user=null)
    {
        $this->setUser($user);
    }

    public function setUser(User $user=null)
    {
        return $this->user = $user ? $user : Auth::user();
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getAvailable($columns = ['*'])
    {
        return Bonus::availableBonuses($this->user)
            ->get($columns);
    }

    public function getActive($columns = ['*'])
    {
        return UserBonus::belongsToUser($this->user->id)
            ->active()
            ->get($columns);
    }

    public function getConsumed($columns = ['*'])
    {
        return UserBonus::belongsToUser($this->user->id)
            ->consumed()
            ->get($columns);
    }

    public function hasActive()
    {
        return UserBonus::belongsToUser($this->user->id)
            ->active()
            ->count() > 0;
    }

    public function isAvailable($bonusId)
    {
        return Bonus::availableBonuses($this->user)
                ->hasBonus($bonusId)
                ->count() > 0;
    }

    public function redeemBonus($bonusId)
    {
        DB::transaction(function() use ($bonusId) {
            $bonus = Bonus::findOrFail($bonusId);

            UserBonus::create([
                'user_id' => $this->user->id,
                'bonus_id' => $bonusId,
                'bonus_head_id' => $bonus->head_id,
                'deadline_date' => Carbon::now()->addDay($bonus->deadline),
                'active' => 1,
            ]);
        });
    }

    public function cancel($bonusId)
    {
        DB::transaction(function() use ($bonusId) {
            $userBonus = UserBonus::findOrFail($bonusId);
            $userBonus->active = 0;
            $userBonus->save();

            $bonusAmount = $this->user->balance->getBonus();
            $this->user->balance->subtractBonus($bonusAmount);
        });
    }


    public function isCancellable()
    {

    }
}