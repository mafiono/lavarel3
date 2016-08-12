<?php


namespace App\Bonus\Queries;

use App\Bonus;
use App\User;
use Carbon\Carbon;
use DB;

class AvailableBonusesQuery
{
    private $builder;
    private $user;

    function __construct(User $user)
    {
        $this->user = $user;
    }

    public function get($columns = ['*'])
    {
        $this->builder = Bonus::query();

        $this->current();

        $this->availableNow();

        $this->notActive();

        $this->firstDeposit();

        $this->targets();

        return $this->builder->get($columns);
    }

    private function current()
    {
        $this->builder->where('bonus.current','1');
    }

    private function availableNow()
    {
        $this->builder->whereDate('bonus.available_from', '<=', Carbon::now()->format('Y-m-d'))
            ->whereDate('bonus.available_until', '>=', Carbon::now()->format('Y-m-d'));
    }

    private function notActive()
    {
        $this->builder->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('user_bonus')
                ->where('user_bonus.user_id', '=', $this->user->id);
        });
    }

    private function targets()
    {
        $this->builder->where(function($query) {
            $this->targetGroups($query);

            $this->targetUsers($query);
        });
    }

    private function firstDeposit()
    {
        $this->builder->where(function ($query) {
            $query->where('bonus_type_id', '!=', 'first_deposit')
                ->orWhereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                    ->from('user_transactions')
                    ->where('user_transactions.status_id', 'processed')
                    ->where('user_transactions.user_id', $this->user->id);
                });
        });
    }

    // check if target_id in bonus_targets
    private function targetGroups($query)
    {
        $query->whereExists(function ($query)
        {
            $query->select(DB::raw(1))
                ->from('bonus_targets')
                ->whereRaw('bonus_targets.bonus_id = bonus.id')
                ->where(function ($query) {
                    $query->where('target_id', '=', $this->user->rating_risk)
                        ->orWhere('target_id', '=', $this->user->rating_group)
                        ->orWhere('target_id', '=', $this->user->rating_type)
                        ->orWhere('target_id', '=', $this->user->rating_class);
                });
        });
    }

    // check if username in bonus_username_targets
    private function targetUsers($query)
    {
        $query->orWhereExists(function ($query)
        {
            $query->select(DB::raw(1))
                ->from('bonus_username_targets')
                ->whereRaw('bonus_username_targets.bonus_id = bonus.id')
                ->where('username', '=', $this->user->username);
        });

    }

}