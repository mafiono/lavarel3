<?php

namespace App\Console\Commands;

use SportsBonus;
use Exception;
use App\UserBonus;
use Carbon\Carbon;
use Illuminate\Console\Command;


class BonusCancellerCommand extends Command
{
    protected $signature = 'cancel-bonuses';

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
            try {
                SportsBonus::swapUser($bonus->user, $bonus);
                SportsBonus::forceCancel();
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }

    }

}