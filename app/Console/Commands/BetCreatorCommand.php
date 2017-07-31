<?php

namespace app\Console\Commands;


use App\Bets\Bets\BetslipBet;
use App\Bets\Bookie\BetBookie;
use App\Bets\Collectors\BetslipCollector;
use App\Bets\Models\Competition;
use App\Bets\Models\Fixture;
use App\Bets\Models\Market;
use App\Bets\Models\Region;
use App\Bets\Models\Selection;
use App\Bets\Models\Sport;
use App\Bets\Resolvers\BetResolver;
use App\Bets\Validators\BetslipBetValidator;
use App\User;
use App\UserBet;
use App\UserBetEvent;
use App\UserBetslip;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Console\Command;


class BetCreatorCommand extends Command
{
    protected $signature = 'create-all-bets {debug?}';

    protected $description = 'Creates a bet for all markets';

    private $queryCollector;
    private $timeQueries;

    public function handle()
    {
        $user = User::findById(404);
        $sessionId = $user->getLastSession()->id;
        $today = Carbon::now()->hour(0)->minute(0)->second(0)->toDateTimeString();

        $query = DB::table(Selection::alias('s'))
            ->leftJoin(Market::alias('m'), 's.market_id', '=', 'm.id')
            ->leftJoin(Fixture::alias('f'), 'm.fixture_id', '=', 'f.id')
            ->leftJoin(Competition::alias('c'), 'f.competition_id', '=', 'c.id')
            ->leftJoin(Sport::alias('sp'), 'f.sport_id', '=', 'sp.id')
            ->leftJoin(Region::alias('r'), 'c.region_id', '=', 'r.id')
            ->leftJoin(UserBetEvent::alias('be'), function ($join) use($today) {
                $join->on('be.api_event_id', '=', 's.id');
                $join->where('be.created_at', '>', $today);
            })
            ->leftJoin(UserBet::alias('ub'), function ($join) use($user) {
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
        $debug = $this->argument('debug') ?? 0;
        if ($debug === '1') {
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
        }

        $apostas = $query
            // ->limit(200)
            ->get();

        $count = 0;
        $success = 0;
        $total = count($apostas);
        $this->line("Submetidas $total apostas!");

        $this->listDb();

        foreach ($apostas as $betSel) {
            $count++;
            if ($count % 50 === 0) {
                $date = Carbon::now()->format('H:i:s');
                $this->line("$date: Submetidas $count/$total apostas! $success com sucesso!");
            }
            try {
                DB::transaction(function () use($betSel, $user, $sessionId) {
                    $sel = DB::table(Selection::alias('s'))
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
                if ($debug === '2') {
                    dd($this->queryCollector, $this->timeQueries);
                }
                $success++;
            } catch (\Exception $e) {
                $this->line("Error: " . $betSel->id . ': ' . $betSel->gameName . " -> " . $e->getMessage());
            }
            $this->queryCollector = [];
            $this->timeQueries = (float)0;
        }
        $this->line("Finish");
    }

    private function listDb()
    {
        $app = app();
        $db = $app['db'];
        $this->queryCollector = [];
        $this->timeQueries = (float)0;
        try {
            $db->listen(
                function ($query, $bindings = null, $time = null, $connectionName = null) use ($db) {
                    // Laravel 5.2 changed the way some core events worked. We must account for
                    // the first argument being an "event object", where arguments are passed
                    // via object properties, instead of individual arguments.
                    if ( $query instanceof \Illuminate\Database\Events\QueryExecuted ) {
                        $bindings = $query->bindings;
                        $time = $query->time;
                        $query = $query->sql;
                    }
                    $this->queryCollector[] = [
                        'query' =>(string) $query,
                        'bindings' => $bindings,
                        'time' => $time,
                        // 'connection' => $connection,
                    ];
                    $this->timeQueries += $time;
                }
            );
        } catch (\Exception $e) {
            dd("Why");
            new Exception(
                'Cannot add listen to Queries for Laravel Debugbar: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
}