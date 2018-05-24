<?php

namespace App\Console\Commands;

use Anchu\Ftp\Facades\Ftp;
use App\Bonus;
use App\CasinoTransaction;
use App\GlobalSettings;
use App\Models\Affiliate;
use App\Models\CasinoRound;
use App\User;
use App\UserBet;
use App\UserBetStatus;
use App\UserBetTransaction;
use App\UserBonus;
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
        $pathSales = storage_path('/afiliates/' . $nameSales);
        $outsales = fopen($pathSales, 'w');
        fputcsv($outsales, ['BTAG', 'BRAND', 'TRANSACTION_DATE', 'PLAYER_ID', 'CHARGEBACK', 'DEPOSITS', 'DEPOSITS_COUNT', 'CASINO_BETS', 'CASINO_REVENUE', 'CASINO_BONUSES', 'CASINO_STAKE', 'CASINO_NGR', 'SPORTS_BONUSES', 'SPORTS_REVENUE', 'SPORTS_BETS', 'SPORTS_STAKE', 'SPORTS_NGR']);

        foreach ($users as $user) {
            $skip = true;
            $affiliate = Affiliate::where('prefix',substr($user->promo_code, 0, strpos($user->promo_code, '_')))->first();

            if($affiliate === null)
            {
                $affiliate = Affiliate::where('prefix',1)->first();
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
                $skip = false;
            }
            if (isset($userBetTrans->won)) {
                $bets->won += $userBetTrans->won->amount;
                //$bets->won_bonus += $userBetTrans->won->amount_bonus;
                $skip = false;
            }
            if (isset($userBetTrans->returned)) {
                $bets->won += $userBetTrans->returned->amount;
                //$bets->won_bonus += $userBetTrans->returned->amount_bonus;
                $skip = false;
            }

                $usercasinobets = DB::table(CasinoTransaction::alias('ct'))
                    ->leftJoin(CasinoRound::alias('cr'), 'ct.round_id', '=', 'cr.id')
                    ->whereNull('cr.user_bonus_id')
                    ->where('ct.created_at', '>=', $date)
                    ->where('ct.created_at', '<', $to)
                    ->where('ct.user_id', '=', $user->id)
                    ->select([
                        DB::raw('count(distinct ct.round_id) as count'),
                        DB::raw("sum(CASE WHEN type = 'bet' THEN amount ELSE 0 END) as amount"),
                        DB::raw("sum(CASE WHEN type = 'win' THEN amount ELSE 0 END) as amount_win"),
                    ])
                    ->first()
                ;

            $user->casinobets = $usercasinobets->count ?? 0;
            $user->casinostake = $usercasinobets->amount ?? 0;
            $user->casinorevenue = $user->casinostake - ($usercasinobets->amount_win ?? 0);
            $user->casinobonus = abs($user->casinorevenue * $affiliate->bonuscasino/100);
            $user->casinoNGR = $user->casinorevenue - abs($affiliate->iejocasino/100 * $user->casinorevenue) - $user->casinobonus - abs($affiliate->depositcasino/100 * $user->casinorevenue);
            $user->sportbets = $bets->bets;
            $user->sportstake = $bets->amount;
            $user->sportrevenue = $user->sportstake - $bets->won;
            $sportbonus = abs($affiliate->bonussb/100 * $user->sportrevenue);
            $user->sportNGR = $user->sportrevenue - abs($affiliate->iejosb/100 * $user->sportstake) - $sportbonus - abs($affiliate->depositsb/100 * $user->sportrevenue);


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
            $deposits30days = UserTransaction::query()
                ->where('created_at', '>=', Carbon::now()->subDay(30))
                ->where('created_at', '<', Carbon::now())
                ->where('user_id', '=', $user->id)
                ->where('debit', '>', 0)
                ->where('status_id', '=', 'processed')
                ->select([
                    DB::raw('count(*) as count'),
                    DB::raw('sum(debit) as debit'),
                ])
                ->first();
            $userBetTrans30days = DB::table(UserBetTransaction::alias('ubt'))
                ->leftJoin(UserBet::alias('ub'), 'ubt.user_bet_id', '=', 'ub.id')
                ->where('ubt.created_at', '>=', Carbon::now()->subDay(30))
                ->where('ubt.created_at', '<', Carbon::now())
                ->where('ub.user_id', '=', $user->id)
                ->select([
                    DB::raw('count(*) as count'),
                    DB::raw('sum(ubt.amount_balance) as amount'),
                ])
                ->get()
            ;
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
                fwrite($outsales, "$user->promo_code,$user->brand," . $date->format('Y-m-d') . ",$user->id,0,$user->deposits,$user->depositscount,$user->casinobets,$user->casinorevenue,$user->casinobonus,$user->casinostake,$user->casinoNGR,$sportbonus,$user->sportrevenue,$user->sportbets,$user->sportstake,$user->sportNGR\r\n");
            }

            $bets30days = $userBetTrans30days->count ?? 0;
            $deposits30dayscount = $deposits30days->count ?? 0;


            if($affiliate->expire && $user->created_at < Carbon::now()->subDay(30) && $bets30days == 0 && $deposits30dayscount == 0)
            {
                $user->promo_code = '';
                User::where('id',$user->id)->update(['promo_code' => '']);
            }
        }
        fclose($outsales);

        $nameReg = 'everymatrix_casinoportugal_reg_' . $date->format('Ymd') . '.csv';
        $pathReg = storage_path('afiliates/' . $nameReg);
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

        if(config('enable_ftp'))
        {
            if (FTP::connection('ftp_afiliados')->uploadFile($pathReg, '/' . $nameReg))
                $this->line("Colocado $nameReg no FTP com sucesso!!");
            else
                $this->line("Erro ao colocar o $nameReg no FTP!");
            if (FTP::connection('ftp_afiliados')->uploadFile($pathSales, '/' . $nameSales))
                $this->line("Colocado $nameSales no FTP com sucesso!!");
            else
                $this->line("Erro ao colocar o $nameSales no FTP!");
        }else{
            $this->line("Gravado sem envio de ftp");
        }
    }
}
