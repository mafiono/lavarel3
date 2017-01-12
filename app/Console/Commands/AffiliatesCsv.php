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


class AffiliatesCsv extends Command
{
    protected $signature = 'affiliates-csv';

    protected $description = 'Cria Csv Afiliados';


    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $users = User::query()->where('promo_code','!=','')->get();
       
        $outsales = fopen('everymatrix_casinoportugal_sales_' .date('Ymd') . '.csv', 'w');
        fputcsv($outsales, ['BTAG','BRAND','TRANSACTION_DATE','PLAYER_ID','CHARGEBACK','DEPOSITS','DEPOSITS_COUNT','CASINO_BETS','CASINO_REVENUE','CASINO_BONUSES','CASINO_STAKE','CASINO_NGR','SPORTS_BONUSES','SPORTS_REVENUE','SPORTS_BETS','SPORTS_STAKE','SPORTS_NGR']);

        foreach($users as $user) {
            $user->sportbets = UserBet::query()->where('created_at', '>', Carbon::now()->subDays(1))->where('user_id', '=', $user->id)->count();
            $user->sportstake = UserBet::query()->where('created_at', '>', Carbon::now()->subDays(1))->where('user_id', '=', $user->id)->sum('amount');
            $user->sportrevenue = $user->sportstake - UserBet::query()->where('created_at', '>', Carbon::now()->subDays(1))->where('user_id', '=', $user->id)->sum('result_amount');
            $user->sportNGR = $user->sportrevenue - (0.08 * $user->sportstake);
            $user->sportbonus = UserBetTransaction::whereHas('bet', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('created_at', '>', Carbon::now()->subDays(1))->where('operation', '=', 'withdrawal')->sum('amount_bonus') ? UserBetTransaction::whereHas('bet', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('created_at', '>', Carbon::now()->subDays(1))->where('operation', '=', 'withdrawal')->sum('amount_bonus') : 0;
            $user->casinobets = CasinoTransaction::query()->where('created_at', '>', Carbon::now()->subDays(1))->where('type', '=', 'bet')->where('user_id', '=', $user->id)->count();
            $user->casinostake = CasinoTransaction::query()->where('created_at', '>', Carbon::now()->subDays(1))->where('type', '=', 'bet')->where('user_id', '=', $user->id)->sum('amount') ? CasinoTransaction::query()->where('type', '=', 'bet')->where('user_id', '=', $user->id)->sum('amount') : 0;
            $user->casinorevenue = $user->casinostake - CasinoTransaction::query()->where('created_at', '>', Carbon::now()->subDays(1))->where('user_id', '=', $user->id)->where('type', '=', 'win')->sum('amount');
            $user->casinobonus = CasinoTransaction::query()->where('created_at', '>', Carbon::now()->subDays(1))->where('type', '=', 'bet')->sum('amount_bonus');
            $user->casinoNGR = $user->casinorevenue - (GlobalSettings::getTax() * $user->casinorevenue);
            $user->deposits = UserTransaction::query()->where('created_at', '>', Carbon::now()->subDays(1))->where('user_id', '=', $user->id)->where('debit', '>', 0)->where('status_id', '=', 'processed')->sum('debit');
            $user->depositscount = UserTransaction::query()->where('created_at', '>', Carbon::now()->subDays(1))->where('user_id', '=', $user->id)->where('debit', '>', 0)->where('status_id', '=', 'processed')->count();
            if ($user->deposits == null) {
                $user->deposits = 0;
            }
            if ($user->sportsNGR == null) {
                $user->sportsNGR = 0;
            }
            if ($user->sportsbets == null) {
                $user->sportsbets = 0;
            }
            if ($user->casinobonus == null) {
                $user->casinobonus = 0;
            }
            if ($user->sportstake == null) {
                $user->sportstake = 0;
            }


            $user->brand = 'CasinoPortugal.pt';
            if ($user->sportbets != 0 or $user->casinobets != 0 or $user->deposits != 0) {

            fwrite($outsales, "$user->promo_code,$user->brand," . date('d/m/Y') . ",$user->id,0,$user->deposits,$user->depositscount,$user->casinobets,$user->casinorevenue,$user->casinobonus,$user->casinostake,$user->casinoNGR,$user->sportbonus,$user->sportrevenue,$user->sportbets,$user->sportstake,$user->sportNGR\r\n");
             }
        }
        fclose($outsales);

        $outreg = fopen('everymatrix_casinoportugal_reg_' .date('Ymd') . '.csv', 'w');


        $users = User::has('profile')->where('promo_code','!=','')->where('created_at','>',Carbon::now()->subDays(1))->get();

        fputcsv($outreg, ['BTAG','BRAND','ACCOUNT_DATE','PLAYER_ID','USERNAME','COUNTRY']);
        foreach($users as $user) {


            fwrite($outreg,"$user->promo_code,CasinoPortugal.pt,".date('d/m/Y').",$user->id,$user->username,". $user->profile->country ."\r\n");
        }

            FTP::connection('connection1')->uploadFile($outreg, $outreg);
            FTP::connection('connection1')->uploadFile($outsales, $outsales);
    }

}
