<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\UserBetEvent;
use DB;
use Illuminate\Http\Request;
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

    public function operations(Request $request) {
        $user_bets = UserBet::where('user_id', $this->authUser->id)->take(50)->get();
        $input = $request->all();
        return view('portal.history.operations_history', compact('user_bets', 'input'));
    }

    public function operationsPost(Request $request) {
        $props = $request->all();

        $trans = UserTransaction::where('user_id', $this->authUser->id)
            ->where('date', '>=', \Carbon\Carbon::createFromFormat('d/m/y H', $props['date_begin'] . ' 0'))
            ->where('date', '<', \Carbon\Carbon::createFromFormat('d/m/y H', $props['date_end'] . ' 24'))
            ->where('status_id', '=', 'processed')
            ->select('id', DB::raw('`date`, `origin` as `type`, `description`, ' .
                'status_id as status,' .
                'CONVERT(IFNULL(`debit`, 0) - IFNULL(`credit`, 0), DECIMAL(15,2)) as `value`, ' .
                'CONVERT(0, DECIMAL(15,2)) as `tax`'));

        $bets = UserBet::where('user_id', $this->authUser->id)
            ->where('created_at', '>=', \Carbon\Carbon::createFromFormat('d/m/y H', $props['date_begin'] . ' 0'))
            ->where('created_at', '<', \Carbon\Carbon::createFromFormat('d/m/y H', $props['date_end'] . ' 24'))
            ->select('id', DB::raw('`created_at` as `date`, ' .
                '`api_bet_type` as `type`, ' .
                '`api_bet_id` as `description`, ' .
                'status,' .
                'CONVERT(IFNULL(`result_amount`, 0) - IFNULL(`amount`, 0), DECIMAL(15,2)) as `value`,' .
                'CONVERT(IFNULL(`amount_taxed`, 0), DECIMAL(15,2)) as `tax`'));

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
        if (isset($props['casino_bets_filter']) && isset($props['sports_bets_filter'])) {

        } else if (isset($props['casino_bets_filter'])) {
            $bets = $bets->where('api_bet_type', '=', 'nyx-casino');
        } else if (isset($props['sports_bets_filter'])) {
            $bets = $bets->where('api_bet_type', '=', 'betportugal');
        } else {
            $ignoreBets = true;
        }

        if ($ignoreTrans && $ignoreBets) {
            return "[]";
            // $result = $trans->union($bets);
        } else if ($ignoreTrans) {
            $result = $bets;
        } else if ($ignoreBets) {
            $result = $trans;
        } else {
            $result = $trans->union($bets);
        }
        $result = $result
            ->orderBy('date', 'DESC');

        $results = $result->get();

        foreach ($results as $result) {
            if ($result->type === 'betportugal') {
                $result->type = 'sportsbook';
                $result->description = 'aposta nº '.$result->description;
            }
        }

        return $results->toJson();
    }

    public function betDetails($id)
    {
        $bet = UserBet::fromUser(Auth::user()->id)
            ->with('events')
            ->find($id);

        return compact('bet');
    }
}