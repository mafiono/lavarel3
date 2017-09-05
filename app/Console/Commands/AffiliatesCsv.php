<?php

namespace App\Console\Commands;

use Anchu\Ftp\Facades\Ftp;
use App\CasinoTransaction;
use App\Models\Affiliate;
use App\User;
use App\UserBet;
use App\UserBetStatus;
use App\UserBetTransaction;
use App\UserTransaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
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
        $date = $this->argument('date') ?: Carbon::now()->subHours(1)->format('Y-m-d');
        $date = Carbon::parse($date);
        $to = $date->copy()->addDay(1);

        $users = User::query()->where('promo_code', '!=', '')->get();

        $nameSales = 'everymatrix_casinoportugal_sales_' . $date->format('Ymd') . '.csv';
        $pathSales = 'storage/afiliates/' . $nameSales;
        $outsales = fopen($pathSales, 'w');
        fputcsv($outsales, ['BTAG', 'BRAND', 'TRANSACTION_DATE', 'PLAYER_ID', 'CHARGEBACK', 'DEPOSITS', 'DEPOSITS_COUNT', 'CASINO_BETS', 'CASINO_REVENUE', 'CASINO_BONUSES', 'CASINO_STAKE', 'CASINO_NGR', 'SPORTS_BONUSES', 'SPORTS_REVENUE', 'SPORTS_BETS', 'SPORTS_STAKE', 'SPORTS_NGR']);

        foreach ($users as $user) {
            $skip = true;
            $deposits = UserTransaction::where('user_id', $user->id)->where('debit', '>', 0)->sum('debit');
            if ($deposits >= 10) {
                if (($affiliate = Affiliate::where('btag', $user->promo_code)->first()) !== null) {
                    $group = $affiliate->group;
                } else {
                    $group = "SB";
                }
                $userBetTrans = DB::table(UserBetTransaction::alias('ubt'))
                    ->leftJoin(UserBet::alias('ub'), 'ubt.user_bet_id', '=', 'ub.id')
                    ->leftJoin(UserBetStatus::alias('ubs'), 'ubt.user_bet_status_id', '=', 'ubs.id')
                    ->where('ubt.created_at', '>=', $date)
                    ->where('ubt.created_at', '<', $to)
                    ->where('ub.user_id', '=', $user->id)
                    ->groupBy('ubs.status')
                    ->select([
                        'ubs.status',
                        DB::raw('count(*) as count'),
                        DB::raw('sum(ubt.amount_balance) as amount'),
                        DB::raw('sum(ubt.amount_bonus) as amount_bonus'),
                    ])
                    ->get()
                ;

                $userBetTrans = (object)(new Collection($userBetTrans))->keyBy('status')->toArray();
                $bets = (object)[
                    'bets' => 0,
                    'amount' => 0,
                    'bonus' => 0,
                    'won' => 0,
                    'won_bonus' => 0,
                ];
                if (isset($userBetTrans->waiting_result)) {
                    $bets->bets += $userBetTrans->waiting_result->count;
                    $bets->amount += $userBetTrans->waiting_result->amount;
                    $bets->bonus += $userBetTrans->waiting_result->amount_bonus;
                    $skip = false;
                }
                if (isset($userBetTrans->won)) {
                    $bets->won += $userBetTrans->won->amount;
                    $bets->won_bonus += $userBetTrans->won->amount_bonus;
                    $skip = false;
                }
                if (isset($userBetTrans->returned)) {
                    $bets->won += $userBetTrans->returned->amount;
                    $bets->won_bonus += $userBetTrans->returned->amount_bonus;
                    $skip = false;
                }

                $usercasinobets = CasinoTransaction::query()
                    ->where('created_at', '>=', $date)
                    ->where('created_at', '<', $to)
                    ->where('user_id', '=', $user->id)
                    ->select([
                        DB::raw('count(*) as count'),
                        DB::raw('sum(amount) as amount'),
                        DB::raw('sum(amount_bonus) as amount_bonus'),
                        DB::raw("sum(CASE WHEN type = 'win' THEN amount ELSE 0 END) as amount_win"),
                    ])
                    ->first()
                ;
                if ($group === 'SB') {

                    $user->sportbets = $bets->bets;
                    $user->sportstake = $bets->amount;
                    $user->sportrevenue = $user->sportstake - $bets->won;
                    $sportBonus = $user->sportrevenue * 0.2; // TODO: use $bets->bonus
                    $user->sportNGR = $user->sportrevenue - (0.16 * $user->sportstake) - $sportBonus - (0.05 * $user->sportrevenue);

                    $user->casinobets = 0;
                    $user->casinostake = 0;
                    $user->casinorevenue = 0;
                    $user->casinobonus = 0;
                    $user->casinoNGR = 0;
                }
                if ($group === 'Casino') {

                    $user->casinobets = $usercasinobets->count ?? 0;
                    $user->casinostake = $usercasinobets->amount ?? 0;
                    $user->casinorevenue = $user->casinostake - ($usercasinobets->amount_win ?? 0);
                    $casinoBonus = $user->casinorevenue * 0.2;
                    $user->casinoNGR = $user->casinorevenue - (0.20 * $user->casinorevenue) - $casinoBonus - 0.05 * $user->casinorevenue;
                    $user->sportbets = 0;
                    $user->sportstake = 0;
                    $user->sportrevenue = 0;
                    $user->sportNGR = 0;
                    $sportBonus = 0;
                }
                else {
                    $user->casinobets = $usercasinobets->count ?? 0;
                    $user->casinostake = $usercasinobets->amount ?? 0;
                    $user->casinorevenue = $user->casinostake - ($usercasinobets->amount_win ?? 0);
                    $casinoBonus = $user->casinorevenue * 0.2;
                    $user->casinoNGR = $user->casinorevenue - (0.20 * $user->casinorevenue) - $casinoBonus - 0.05 * $user->casinorevenue;
                    $user->sportbets = $bets->bets;
                    $user->sportstake = $bets->amount;
                    $user->sportrevenue = $user->sportstake - $bets->won;
                    $sportBonus = $user->sportrevenue * 0.2;
                    $user->sportNGR = $user->sportrevenue - (0.16 * $user->sportstake) - $sportBonus - 0.05 * $user->sportrevenue;
                }

                $deposits = UserTransaction::query()
                    ->where('created_at', '>=', $date)
                    ->where('created_at', '<', $to)
                    ->where('user_id', '=', $user->id)
                    ->where('debit', '>', 0)
                    ->where('status_id', '=', 'processed')
                    ->select([
                        DB::raw('count(*) as count'),
                        DB::raw('sum(debit) as debit'),
                    ])
                    ->first();
                $user->deposits = $deposits->debit ?? 0;
                $user->depositscount = $deposits->count ?? 0;

                if ($user->sportsNGR === null) {
                    $user->sportsNGR = 0;
                }
                if ($user->sportsbets === null) {
                    $user->sportsbets = 0;
                }
                if ($user->casinobonus === null) {
                    $user->casinobonus = 0;
                }
                if ($user->sportstake === null) {
                    $user->sportstake = 0;
                }

                $user->brand = 'CasinoPortugal.pt';
                if (!$skip || $user->sportbets !== 0 || $user->casinobets !== 0 || $user->deposits !== 0) {
                    fwrite($outsales, "$user->promo_code,$user->brand," . $date->format('Y-m-d') . ",$user->id,0,$user->deposits,$user->depositscount,$user->casinobets,$user->casinorevenue,$user->casinobonus,$user->casinostake,$user->casinoNGR,$sportBonus,$user->sportrevenue,$user->sportbets,$user->sportstake,$user->sportNGR\r\n");
                }
            }
        }
        fclose($outsales);

        $nameReg = 'everymatrix_casinoportugal_reg_' . $date->format('Ymd') . '.csv';
        $pathReg = 'storage/afiliates/' . $nameReg;
        $outreg = fopen($pathReg, 'w');

        $users = User::has('profile')
            ->where('promo_code', '!=', '')
            ->where('created_at', '>=', $date)
            ->where('created_at', '<', $to)
            ->get();

        fputcsv($outreg, ['BTAG', 'BRAND', 'ACCOUNT_OPENING_DATE', 'PLAYER_ID', 'USERNAME', 'COUNTRY']);
        foreach ($users as $user) {
            fwrite($outreg, "$user->promo_code,CasinoPortugal.pt," . $date->format('Y-m-d') . ",$user->id,player_$user->id," . $user->profile->country . "\r\n");
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
