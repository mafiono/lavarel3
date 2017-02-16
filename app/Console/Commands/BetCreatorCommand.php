<?php

namespace app\Console\Commands;


use App\Bets\Bets\BetslipBet;
use App\Bets\Bookie\BetBookie;
use App\Bets\Collectors\BetslipCollector;
use App\Bets\Models\Selection;
use App\Bets\Resolvers\BetResolver;
use App\Bets\Validators\BetslipBetValidator;
use App\User;
use App\UserBetslip;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;


class BetCreatorCommand extends Command
{
    protected $signature = 'create-all-bets';

    protected $description = 'Creates a bet for all markets';

    public function handle()
    {
        $user = User::findById(404);
        $sessionId = $user->getLastSession()->id;
        $today = Carbon::now()->hour(0)->minute(0)->second(0)->toDateTimeString();

        $query = DB::table('betgenius.selections as s')
            ->leftJoin('betgenius.markets as m', 's.market_id', '=', 'm.id')
            ->leftJoin('betgenius.fixtures as f', 'm.fixture_id', '=', 'f.id')
            ->leftJoin('betgenius.competitions as c', 'f.competition_id', '=', 'c.id')
            ->leftJoin('betgenius.sports as sp', 'f.sport_id', '=', 'sp.id')
            ->leftJoin('betgenius.regions as r', 'c.region_id', '=', 'r.id')
            ->leftJoin('betportugal.user_bet_events as be', function ($join) use($today) {
                $join->on('be.api_event_id', '=', 's.id');
                $join->where('be.created_at', '>', $today);
            })
            ->leftJoin('betportugal.user_bets as ub', function ($join) use($user) {
                $join->on('ub.id', '=', 'be.user_bet_id');
                $join->where('ub.user_id', '=', $user->id);
            })
            ->select([
                's.id',
                's.name',
                's.decimal as odds',
                'm.id as marketId',
                'm.name as marketName',
                'f.id as gameId',
                'f.name as gameName',
                'f.start_time_utc as gameDate',
            ])
            ->whereNull('be.id')
            //->whereNull('c.authorized')
            ->where('c.authorized', '=', '1')
            ->where('s.decimal', '>', 1.08)
            //->whereIn('m.market_type_id', [2, 322, 306, 6662, 7469, 8133, 15, 6734,])
            ->where('f.status', '=', 'Scheduled')
            ->where('f.fixture_type', '=', 'Match')
            //->where('f.start_time_utc', '>', Carbon::now()->tz('UTC')->subHours(12))
            ->whereNotNull('f.srij_event_id')
            ->where('f.start_time_utc', '>', Carbon::now()->tz('UTC'))
            //->limit(10)
            ;
        dd($query
//            ->groupBy('c.id')
//            ->select([
//                'sp.name as sport', 'r.name as region', 'c.id', 'c.name', DB::raw('count(distinct f.id) as Games')
//            ])
//            ->orderBy(DB::raw('count(distinct f.id)'), 'desc')
//            ->limit(10)
            ->select([
                DB::raw('count(distinct f.id) as Games'),
                DB::raw('count(distinct s.id) as Apostas')
            ])
            ->get()
        );

        $apostas = $query
            // ->limit(200)
            ->get();

        $count = 0;
        $success = 0;
        $total = count($apostas);
        $this->line("Submetidas $total apostas!");
        foreach ($apostas as $betSel) {
            $count++;
            if ($count % 50 === 0) {
                $this->line("Submetidas $count/$total apostas! $success com sucesso!");
            }
            try {
                DB::transaction(function () use($betSel, $user, $sessionId) {
                    $sel = DB::table('betgenius.selections as s')
                        ->where('s.id', '=', $betSel->id)
                        ->first();

                    $betArray = [
                        "rid" => $betSel->id, // bet id -> selection ID from betgenius
                        "amount" => 2, // valor da aposta 2 euros
                        "type" => "simple", // apenas vamos criar simples
                        "events" => [[
                            "id" => $betSel->id, // selection ID
                            "odds" => $sel->decimal, // valor da Odd
                            "name" => $betSel->name, // Casa, Fora etc :(
                            "marketId" => $betSel->marketId, // id do market
                            "marketName" => $betSel->marketName, // Nome do mercado :(
                            "gameId" => $betSel->gameId, // id do fixture
                            "gameName" => $betSel->gameName, // nome do Jogo
                            "gameDate" => $betSel->gameDate, // Data do jogo
                        ]]
                    ];
                    $newBetslip = UserBetslip::create(['user_id' => $user->id]);
                    // for 1 bet it is
                    $bet = BetslipBet::make($betArray, $newBetslip->id, $user, $sessionId);

                    BetslipBetValidator::make($bet)->validate();

                    BetBookie::placeBet($bet, $sessionId);

                });
                $success++;
            } catch (\Exception $e) {
                $this->line("Error: " . $betSel->id . ': ' . $betSel->gameName . " -> " . $e->getMessage());
            }
        }
        $this->line("Finish");
    }
}