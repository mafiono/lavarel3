<?php

namespace App\Console\Commands;

use CasinoBonus;
use Log;
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
        $this->cancelSports();

        $this->cancelCasino();
    }

    protected function cancelSports()
    {
        UserBonus::origin('sport')
            ->active()
            ->where('deadline_date', '<', Carbon::now())
            ->whereDoesntHave('userBets', function ($query) {
                $query->unresolvedBets();
            })->with('user')
            ->get()
            ->each(function ($bonus) {
                try {
                    SportsBonus::swapUser($bonus->user, $bonus);
                    SportsBonus::forceCancel();
                } catch (Exception $e) {
                    Log::error($e->getMessage());
                }
            });
    }

    protected function cancelCasino()
    {
        UserBonus::origin('casino')
            ->active()
            ->doesntHaveActiveRounds()
            ->where('deadline_date', '<', Carbon::now())
            ->with('user')
            ->get()
            ->each(function ($bonus) {
                try {
                    CasinoBonus::swapUser($bonus->user, $bonus);
                    CasinoBonus::forceCancel();
                } catch (Exception $e) {
                    Log::error($e->getMessage());
                }
            });
    }
}
