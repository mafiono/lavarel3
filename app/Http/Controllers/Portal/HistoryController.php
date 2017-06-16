<?php

namespace App\Http\Controllers\Portal;

use App\Bonus;
use App\Models\CasinoTransaction;
use App\Http\Controllers\Controller;
use App\UserBetEvent;
use App\UserBetTransaction;
use App\UserBonus;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Lang;
use Response;
use Symfony\Component\Debug\Exception\FatalErrorException;
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

    public function operationsPost(Request $request) {
        $props = $request->all();

        $results = collect();

        $trans = UserTransaction::where('user_id', '=', $this->authUser->id)
            ->where('date', '>=', Carbon::createFromFormat('d/m/y H', $props['date_begin'] . ' 0'))
            ->where('date', '<', Carbon::createFromFormat('d/m/y H', $props['date_end'] . ' 24'))
            ->whereIn('status_id', ['processed'])
            ->select([
                'id',
                DB::raw(
                '`id` as `uid`,' .
                '`date`,' .
                '`origin` as `type`, '.
                '`description`, ' .
                'status_id as status,' .
                'status_id as operation,' .
                'final_balance,' .
                'CONVERT(IFNULL(`debit`, 0) - IFNULL(`credit`, 0), DECIMAL(15,2)) as `value`, ' .
                'CONVERT(0, DECIMAL(15,2)) as `tax`'
                )
            ]);

        $bets = UserBet::query()
            ->leftJoin(UserBetTransaction::alias('ubt'), 'user_bets.id', '=', 'ubt.user_bet_id')
            ->where('user_bets.user_id', '=', $this->authUser->id)
            ->where('user_bets.created_at', '>=', Carbon::createFromFormat('d/m/y H', $props['date_begin'] . ' 0'))
            ->where('user_bets.created_at', '<', Carbon::createFromFormat('d/m/y H', $props['date_end'] . ' 24'))
            ->where('ubt.amount_balance', '>', '0')
            ->select([
                'user_bets.id',
                DB::raw(
                'ubt.`id` as `uid`, ' .
                'ubt.`created_at` as `date`, ' .
                'user_bets.`api_bet_type` as `type`, ' .
                'user_bets.`api_bet_id` as `description`, ' .
                'user_bets.status,' .
                'ubt.operation,' .
                'CONVERT(ubt.`final_balance` + ubt.`final_bonus`, DECIMAL(15,2)) as `final_balance`,' .
                'CONVERT(ubt.`amount_balance` + ubt.`amount_bonus`, DECIMAL(15,2)) as `value`,' .
                'CONVERT(IFNULL(user_bets.`amount_taxed`, 0), DECIMAL(15,2)) as `tax`'
                )
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
                    $result->description = 'Aposta nº '.$result->description;
                    if ($result->operation === 'deposit') {
                        $result->description = 'Ganhos da '.$result->description;
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
            $casinoTransactions = $this->fetchCasinoTransactions(
                Carbon::createFromFormat('d/m/y', $props['date_begin'])->startOfDay(),
                Carbon::createFromFormat('d/m/y', $props['date_end'])->endOfDay()
            );

            $results = array_merge($results, $casinoTransactions->toArray());

            usort($results, function ($a, $b) {
                return strcmp($b['date'], $a['date']) ? strcmp($b['date'], $a['date']) : strcmp($b['id'], $a['id']);
            });
        }

        return $results;
    }

    public function betDetails($id)
    {
        $bet = UserBet::query()
            ->fromUser($this->authUser->id)
            //->with('events')
            ->find($id);

        $list = DB::table('user_bet_events as ube')
            ->leftJoin('betgenius.fixtures as f', 'ube.api_game_id', '=', 'f.id')
            ->leftJoin('betgenius.competitions as c', 'f.competition_id', '=', 'c.id')
            ->leftJoin('betgenius.sports as s', 'f.sport_id', '=', 's.id')
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
                's.name as sport_name',
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

    protected function fetchCasinoTransactions($since, $until)
    {
        return CasinoTransaction::whereUserId($this->authUser->id)
            ->whereBetween('created_at', [$since, $until])
            ->whereTransactionstatus('ok')
            ->with(['game', 'round'])
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'uid' => $transaction->user_id,
                    'date' => $transaction->created_at->format('Y-m-d H:i:s'),
                    'type' => 'betcasino',
                    'description' => 'Aposta nº ' . $transaction->round->id . ' (' .  $transaction->game->name .')',
                    'status' => $transaction->type,
                    'final_balance' => $transaction->final_balance,
                    'value' => number_format(($transaction->type === 'bet' ? -1 : 1) * $transaction->amount/100, 2),
                    'tax' => '0.00',
                ];
            });
    }
}
