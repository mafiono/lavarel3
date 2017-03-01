<?php
namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Highlight;
use App\UserBet;
use App\UserBetEvent;
use Carbon\Carbon;
use Session, View, Response, Auth, Mail, Validator;
use Illuminate\Http\Request;

class BetsController extends Controller
{
    protected $authUser;
    protected $request;
    protected $userSessionId;
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->authUser = Auth::user();
        $this->userSessionId = Session::get('user_session');
        View::share('authUser', $this->authUser, 'request', $request);
    }

    public function sports()
    {
        $games = UserBetEvent::selectRaw('api_game_id, count(id) as count')
            ->where('game_date','>',Carbon::now())
            ->groupBy('api_game_id')
            ->latest('count')
            ->take(10)
            ->get()
            ->pluck('api_game_id');


        $phpAuthUser = $this->authUser?[
            "id" => $this->authUser->id,
            "auth_token" => $this->authUser->api_password
        ]:null;

        $casino = false;

        $competitions = Highlight::competitions()->lists('highlight_id')->toJson();

        return view('portal.bets.sports', compact("phpAuthUser", "competitions", "games", "casino"));
    }

    protected function highlights()
    {
        $competitions = Highlight::competitions()->lists('highlight_id');

        return $competitions;
    }



    //TODO: hide some fields
    public function openBets()
    {
        $bets = UserBet::fromUser(Auth::user()->id)
            ->waitingResult()
            ->with('events')
            ->get();

        return compact('bets');
    }

}
