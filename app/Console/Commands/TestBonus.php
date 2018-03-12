<?php

namespace App\Console\Commands;

use App\Bonus;
use App\User;
use Auth;
use CasinoBonus;
use Illuminate\Console\Command;
use RuntimeException;
use SportsBonus;

class TestBonus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:bonus {user_id} {bonus_id} {amount=-1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Force a bonus';

    protected $api;

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws RuntimeException
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $u = User::find($userId);
        if ($u === null)
            throw new RuntimeException("Invalid User $userId");
        $bonusId = $this->argument('bonus_id');
        $b = Bonus::find($bonusId);
        if ($b === null)
            throw new RuntimeException("Invalid Bonus $bonusId");

        Auth::login($u);
        $amount = $this->argument('amount', null);
        if ($amount !== null) {
            $amount = (float) $amount;
            if ($amount <= 0) {
                $amount = null;
                $this->line("Ignoring Amount and using Default");
            }
        }

        switch ($b->bonus_origin_id){
            case 'sport':
                SportsBonus::redeem($b->id, true, $amount);
                break;
            case 'casino':
                CasinoBonus::redeem($b->id, true, $amount);
                break;
            default:
                throw new RuntimeException("Unknown Bonus type $b->bonus_origin_id");
                break;
        }
        return 'Ok';
    }

}
