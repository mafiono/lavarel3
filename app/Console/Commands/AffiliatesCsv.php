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


class AffiliatesCsv extends Command
{
    protected $signature = 'affiliates-csv {date?}';

    protected $description = 'Cria Csv Afiliados';


    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $dateParam = $this->argument('date') ?: date('Y-m-d');
        $date = new Carbon($dateParam);

        $users = User::query()->where('promo_code', '!=', '')->get();

        $nameSales = 'everymatrix_casinoportugal_sales_' . $date->format('Ymd') . '.csv';
        $pathSales = 'storage/afiliates/' . $nameSales;
        $outsales = fopen($pathSales, 'w');
        fputcsv($outsales, ['BTAG', 'BRAND', 'TRANSACTION_DATE', 'PLAYER_ID', 'CHARGEBACK', 'DEPOSITS', 'DEPOSITS_COUNT', 'CASINO_BETS', 'CASINO_REVENUE', 'CASINO_BONUSES', 'CASINO_STAKE', 'CASINO_NGR', 'SPORTS_BONUSES', 'SPORTS_REVENUE', 'SPORTS_BETS', 'SPORTS_STAKE', 'SPORTS_NGR']);

        $tax = GlobalSettings::getTax();
        foreach ($users as $user) {
            $usersbbets = UserBet::query()
                ->where('created_at', '>=', $date)
                ->where('created_at', '<', $date->copy()->addDay(1))
                ->where('user_id', '=', $user->id)
                ->select([
                    DB::raw('count(*) as count'),
                    DB::raw('sum(amount) as amount'),
                    DB::raw('sum(result_amount) as result_amount'),
                ])->first();
            $usercasinobets = CasinoTransaction::query()
                ->where('created_at', '>=', $date)
                ->where('created_at', '<', $date->copy()->addDay(1))
                ->where('type', '=', 'bet')
                ->where('user_id', '=', $user->id);
            $user->sportbets = $usersbbets->count;
            $user->sportstake = $usersbbets->amount;
            $user->sportrevenue = $user->sportstake - $usersbbets->result_amount;
            $user->sportNGR = $user->sportrevenue - (0.08 * $user->sportstake);
            $sportBonus = UserBetTransaction::whereHas('bet', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
                ->where('created_at', '>=', $date)
                ->where('created_at', '<', $date->copy()->addDay(1))
                ->where('operation', '=', 'withdrawal')
                ->sum('amount_bonus');
            $user->sportbonus = $sportBonus ? $sportBonus : 0;
            $user->casinobets = $usercasinobets->count();
            $user->casinostake = $usercasinobets->sum('amount') ? $usercasinobets->sum('amount') : 0;
            $user->casinorevenue = $user->casinostake - $usercasinobets->where('type', '=', 'win')->sum('amount');
            $user->casinobonus = $usercasinobets->sum('amount_bonus');
            $user->casinoNGR = $user->casinorevenue - ($tax * $user->casinorevenue);
            $user->deposits = UserTransaction::query()
                ->where('created_at', '>=', $date)
                ->where('created_at', '<', $date->copy()->addDay(1))
                ->where('user_id', '=', $user->id)
                ->where('debit', '>', 0)
                ->where('status_id', '=', 'processed')
                ->sum('debit');
            $user->depositscount = UserTransaction::query()
                ->where('created_at', '>=', $date)
                ->where('created_at', '<', $date->copy()->addDay(1))
                ->where('user_id', '=', $user->id)
                ->where('debit', '>', 0)
                ->where('status_id', '=', 'processed')
                ->count();
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
                fwrite($outsales, "$user->promo_code,$user->brand," . $date->format('Y-m-d') . ",$user->id,0,$user->deposits,$user->depositscount,$user->casinobets,$user->casinorevenue,$user->casinobonus,$user->casinostake,$user->casinoNGR,$user->sportbonus,$user->sportrevenue,$user->sportbets,$user->sportstake,$user->sportNGR\r\n");
            }
        }
        fclose($outsales);

        $nameReg = 'everymatrix_casinoportugal_reg_' . $date->format('Ymd') . '.csv';
        $pathReg = 'storage/afiliates/' . $nameReg;
        $outreg = fopen($pathReg, 'w');

        $users = User::has('profile')
            ->where('promo_code', '!=', '')
            ->where('created_at', '>=', $date)
            ->where('created_at', '<', $date->copy()->addDay(1))
            ->get();

        fputcsv($outreg, ['BTAG', 'BRAND', 'ACCOUNT_DATE', 'PLAYER_ID', 'USERNAME', 'COUNTRY']);
        foreach ($users as $user) {
            fwrite($outreg, "$user->promo_code,CasinoPortugal.pt," . $date->format('Y-m-d') . ",$user->id , player_$user->id," . $user->profile->country . "\r\n");
        }
        fclose($outreg);

        if (FTP::connection('ftp_afiliados')->uploadFile($pathReg, '/' . $nameReg))
            $this->line("Colocado $nameReg no FTP com sucesso!!");
        else
            $this->line("Erro ao colocar o $nameReg no FTP!");
        if (FTP::connection('ftp_afiliados')->uploadFile($pathSales, '/' . $nameSales))
            $this->line("Colocado $nameSales no FTP com sucesso!!");
        else
            $this->line("Erro ao colocar o $nameSales no FTP!");
    }
}
