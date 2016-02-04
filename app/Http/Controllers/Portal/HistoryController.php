<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Auth;
use Session;
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
        if ($request["operations_filter"]=='transactions')
            $transactions = UserTransaction::where('user_id', $this->authUser->id)
                ->where('date','>=', \Carbon\Carbon::createFromFormat('d/m/y H',$request->date_begin.' 0'))
                ->where('date','<',\Carbon\Carbon::createFromFormat('d/m/y H',$request->date_end.' 24'))
                ->where('status_id', '=', 'processed')
                ->orderBy('date', 'DESC')
                ->select(['date','description','charge', 'credit'])
                ->get();
        else
            $transactions = UserBet::where('user_id', $this->authUser->id)
                ->where('created_at','>=', \Carbon\Carbon::createFromFormat('d/m/y H',$request->date_begin.' 0'))
                ->where('created_at','<',\Carbon\Carbon::createFromFormat('d/m/y H',$request->date_end.' 24'))
                ->orderBy('created_at', 'DESC')
                ->get();
        return $transactions->toJson();
    }

    public function recentGet() {
        $user_bets = UserBet::where('user_id', $this->authUser->id)
            ->orderBy('created_at', 'DESC')->take(20)->get();
        return view('portal.history.recent_history', compact('user_bets'));
    }

    public function depositsGet() {
        $user_deposits = UserTransaction::where('user_id', $this->authUser->id)->get();
        return view('portal.history.deposits_history', compact('user_deposits'));
    }

    public function withdrawalsGet() {
        $user_withdrawals = UserTransaction::where('user_id', $this->authUser->id)->get();
        return view('portal.history.withdrawals_history', compact('user_withdrawals'));
    }
}