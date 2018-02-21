<?php
namespace App\Http\Controllers\Portal;

use App\GlobalSettings;
use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Adclick;
use App\Models\Golodeouro;
use App\Models\MarketingCampaign;
use App\Models\Highlight;
use App\UserBet;
use App\UserBetEvent;
use Carbon\Carbon;
use Session, View, Response, Auth, Mail, Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

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
        $golodeouro=false;
        $golo = Golodeouro::where('status','active')->first();
        $image = $golo !== null ? json_decode($golo->details)->image : '';

        $highlights = GlobalSettings::query()
            ->where('id', '=', 'highlights.count')
            ->value('value') ?? 4;

        return view('portal.bets.sports', compact('phpAuthUser', 'highlights', 'games', 'casino','golodeouro','image'));
    }

    //TODO: hide some fields
    public function openBets()
    {
        $bets = UserBet::fromUser(Auth::user()->id)
            ->waitingResult()
            ->with('events.fixture')
            ->get()
            ->sortBy(function ($bet) {
                $bet->events->sortBy('gameDate');
                return $bet->events;
            });


        return compact('bets');
    }
}

