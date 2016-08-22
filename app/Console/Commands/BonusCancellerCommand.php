<?php

namespace App\Console\Commands;

use SportsBonus;
use Exception;
use App\UserBonus;
use Carbon\Carbon;
use Illuminate\Console\Command;


class BonusCancellerCommand extends Command
{
    protected $signature = 'bonus-canceller';

    protected $description = 'Cancels expired bonuses.';

    public function handle()
    {
        $bonuses = UserBonus::active()
            ->where('deadline_date', '<', Carbon::now())
            ->whereDoesntHave('userBets', function($query) {
                $query->unresolvedBets();
            })->with('user')
            ->get();

        foreach ($bonuses as $bonus) {
//            try {
                SportsBonus::swap($bonus->user, $bonus);
                SportsBonus::hello();
//            } catch (Exception $e)
//            {
                get_class(SportsBonus);
//            }

        }

    }

}