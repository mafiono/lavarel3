<?php

namespace App\Console\Commands;

use Anchu\Ftp\Facades\Ftp;
use App\CasinoTransaction;
use App\GlobalSettings;
use App\User;
use App\UserBet;
use App\UserBetTransaction;
use App\UserTransaction;
use Carbon\Carbon;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class AffiliatesActivity extends Command
{
    protected $signature = 'affiliates-activity';

    protected $description = 'gere actividade dos afiliados';


    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $users = User::query()
            ->where('promo_code', '!=', '')
            ->where('created_at', '<', Carbon::now()->subDay(30))
            ->get();

        foreach($users as $user)
        {
            $transactions = UserTransaction::where('user_id',$user->id)->where('created_at','>',Carbon::now()->subDay(30))->count();
            $bets = UserBet::where('user_id',$user->id)->where('created_at','>',Carbon::now()->subDay(30))->count();

            if($transactions + $bets < 3)
            {
                $user->promo_code = '';
                $user->save();
            }
        }
    }
}
