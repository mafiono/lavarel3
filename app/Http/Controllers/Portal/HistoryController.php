<?php

namespace App\Http\Controllers\Portal;

use App\Bets\Models\Competition;
use App\Bets\Models\Fixture;
use App\Bets\Models\Sport;
use App\Models\CasinoSession;
use App\Bonus;
use App\Lib\DebugQuery;
use App\Models\CasinoTransaction;
use App\Http\Controllers\Controller;
use App\UserBetEvent;
use App\UserBetTransaction;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Lang;
use View;
use Auth;
use App\UserBet;
use App\UserTransaction;

class HistoryController extends Controller {
    protected $authUser;
    protected $request;

    public function __construct(Request $request) {
        $this->middleware('auth');
        $this->request = $request;
        $this->authUser = Auth::user();
        View::share("authUser", $this->authUser);
    }

    public function operations() {
        return view('portal.history.operations_history');
    }

    public function operationsPost(Request $request)
    {
        $props = $request->all();

        $results = collect();

        $trans = UserTransaction::from(UserTransaction::alias('ut'))
            ->where('user_id', '=', $this->authUser->id)
            ->where('date', '>=', Carbon::createFromFormat('d/m/y H', $props['date_begin'] . ' 0'))
            ->where('date', '<', Carbon::createFromFormat('d/m/y H', $props['date_end'] . ' 24'))
            ->where(function ($q) {
                $q->where(function ($q1) {
                    $q1->where('debit', '>', 0)
                        ->whereIn('status_id', ['processed', 'completed']);
                })->orWhere('credit', '>', 0);
            })
            ->select([
                'id',
                'id as uid',
                'date',
                'origin as type',
                'description',
                'status_id as status',
                'status_id as operation',
                DB::raw('CONVERT(final_balance + final_bonus, DECIMAL(15,2)) as final_balance'),
                DB::raw('CONVERT(debit - credit + debit_bonus, DECIMAL(15,2)) as value'),
                'tax'
            ]);

//        DebugQuery::make($trans);

        $bets = UserBet::from(UserBet::alias('ub'))
            ->leftJoin(UserBetTransaction::alias('ubt'), 'ub.id', '=', 'ubt.user_bet_id')
            ->where('ub.user_id', '=', $this->authUser->id)
            ->where('ub.created_at', '>=', Carbon::createFromFormat('d/m/y H', $props['date_begin'] . ' 0'))
            ->where('ub.created_at', '<', Carbon::createFromFormat('d/m/y H', $props['date_end'] . ' 24'))
            ->where(function ($q){
                $q->where('ubt.amount_balance', '>', '0');
                $q->orWhere('ubt.amount_bonus', '>', '0');
            })
            ->select([
                'ub.id',
                'ubt.id as uid',
                'ubt.created_at as date',
                'ub.api_bet_type as type',
                'ub.api_bet_id as description',
                'ub.status',
                'ubt.operation',
                DB::raw('CONVERT(ubt.final_balance + ubt.final_bonus, DECIMAL(15,2)) as final_balance'),
                DB::raw('CONVERT(ubt.amount_balance + ubt.amount_bonus, DECIMAL(15,2)) as value'),
                DB::raw('CONVERT(IFNULL(ub.amount_taxed, 0), DECIMAL(15,2)) as tax'),
            ]);

        $ignoreTrans = false;
        if (isset($props['deposits_filter']) && isset($props['withdraws_filter'])) {

        } else if (isset($props['deposits_filter'])) {
            $trans = $trans->where('debit', '>', 0);
        } else if (isset($props['withdraws_filter'])) {
            $trans = $trans->where('credit', '>', 0);
        } else {
            $ignoreTrans = true;
        }

        $ignoreBets = false;
        if (isset($props['sports_bets_filter'])) {
            $bets = $bets->where('api_bet_type', '=', 'betportugal');
        } else {
            $ignoreBets = true;
        }

        if (!$ignoreTrans || !$ignoreBets) {
            if ($ignoreTrans) {
                $result = $bets;
            } else {
                if ($ignoreBets) {
                    $result = $trans;
                } else {
                    $result = $trans->union($bets);
                }
            }
            $result = $result->orderBy('date', 'DESC')->orderBy('uid', 'DESC');

            $results = $result->get();

            foreach ($results as $result) {
                if ($result->type === 'betportugal') {
                    $result->type = 'sportsbook';
                    $result->description = 'Aposta '.$result->description;
                    if ($result->operation === 'deposit') {
                        if ($result->status === 'won') {
                            $result->description = 'Ganhos '.$result->description;
                        } elseif ($result->status === 'returned') {
                            $result->description = 'Devolução '.$result->description;
                        }
                    } else {
                        $result->value = '-'.$result->value;
                    }
                }
                if ($result->type === 'paypal') {
                    $result->type = 'Paypal';
                    // $result->description = substr($result->description, 0, strpos($result->description, ' '));
                }
                if ($result->type === 'meo_wallet') {
                    $result->type = 'Meo Wallet';
                    // $result->description = substr($result->description, 0, strpos($result->description, ' '));
                }
                if ($result->type === 'bank_transfer') {
                    $result->type = 'Transferência Bancária';
                    // $result->description = substr($result->description, 0, strpos($result->description, ' '));
                }
            }
        }

        $results = $results->toArray();

        if ($request->exists('casino_bets_filter')) {
            $casinoSessions = $this->fetchCasinoSessions(
                Carbon::createFromFormat('d/m/y', $props['date_begin'])->startOfDay(),
                Carbon::createFromFormat('d/m/y', $props['date_end'])->endOfDay()
            );


            $results = array_merge($results, $casinoSessions);

            usort($results, function ($a, $b) {
                return strcmp($b['date'], $a['date']) ? strcmp($b['date'], $a['date']) : strcmp($b['id'], $a['id']);
            });
        }

        return $results;
    }

    public function betDetails($id)
    {
        $bet = UserBet::from(UserBet::alias('user_bets'))
            ->fromUser($this->authUser->id)
            //->with('events')
            ->find($id);

        $list = DB::table(UserBetEvent::alias('ube'))
            ->leftJoin(Fixture::alias('f'), 'ube.api_game_id', '=', 'f.id')
            ->leftJoin(Competition::alias('c'), 'f.competition_id', '=', 'c.id')
            ->leftJoin(Sport::alias('s'), 'f.sport_id', '=', 's.id')
            ->where('ube.user_bet_id', '=', $bet->id)
            ->select([
                'ube.id',
                'ube.user_bet_id',
                'ube.odd',
                'ube.status',
                'ube.event_name',
                'ube.market_name',
                'ube.game_name',
                'ube.game_date',
                'ube.api_event_id',
                'ube.api_market_id',
                'ube.api_game_id',
                'ube.created_at',
                'ube.updated_at',
                'c.id as competition_id',
                'c.name as competition_name',
                's.id as sport_id',
                's.name as sport',
            ])->get();
        foreach ($list as $item){
            $key = 'competitions.'. $item->competition_id;
            if (Lang::has($key)) $item->competition_name = Lang::get($key);
            $key = 'sports.'. $item->sport_id;
            if (Lang::has($key)) $item->sport_name = Lang::get($key);
        }

        $bet['events'] = $list;

        return compact('bet');
    }

    protected function fetchCasinoSessions($since, $until)
    {
        return CasinoSession::whereUserId($this->authUser->id)
            ->whereBetween('created_at', [$since, $until])
            ->has('rounds')
            ->with(['game', 'rounds.transactions'])
            ->get()
            ->map(function ($session) {
                return [
                    'id' => $session->id,
                    'uid' => $session->user_id,
                    'date' => $session->created_at->format('Y-m-d H:i:s'),
                    'type' => 'casino_session',
                    'description' => 'Sessao nº ' . $session->id . ' (' .  $session->game->name .')',
                    'status' => $session->type,
                    'final_balance' => ($session->final_balance + $session->final_bonus),
                    'value' => $this->sumSessionAmounts($session),
                    'tax' => '0.00',
                ];
            })->toArray();
    }

    protected function sumSessionAmounts($session)
    {
        return number_format(
            $session->rounds->reduce(function ($carry, $round) {
                return
                    $carry
                    - $round->transactions->where('type', 'bet')->sum('amount')
                    - $round->transactions->where('type', 'bet')->sum('amount_bonus')
                    + $round->transactions->where('type', 'win')->sum('amount')
                    + $round->transactions->where('type', 'win')->sum('amount_bonus');
            }),
            2
        );
    }

    public function sessionDetails($id)
    {
        $session = CasinoSession::whereId($id)
            ->with('rounds.transactions')
            ->first();

        return view('portal.history.session_details', compact('session'));
    }
}
