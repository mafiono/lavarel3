<?php
namespace App\Http\Controllers\Portal;

use App\GlobalSettings;
use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Adclick;
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
        if (isset($_GET['ad'])) {
            if(Ad::where('link',$_GET['ad'])->first() != null)
            {
                $ad = Ad::where('link',$_GET['ad'])->first();
                Cookie::queue('ad', $_GET['ad'], 45000);
                $click = new Adclick;
                $click->ad_id = $ad->id;
                $click->ip = get_client_ip();
                $click->save();
            }
        }
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

        $highlights = GlobalSettings::query()
            ->where('id', '=', 'highlights.count')
            ->value('value') ?? 4;

        return view('portal.bets.sports', compact('phpAuthUser', 'highlights', 'games', 'casino'));
    }

    //TODO: hide some fields
    public function openBets()
    {
        $bets = UserBet::fromUser(Auth::user()->id)
            ->waitingResult()
            ->with('events')
            ->get()
            ->sortBy(function ($bet) {
                $bet->events->sortBy('gameDate');

                return $bet->events[0]->gameDate;
            });

        return compact('bets');
    }
}

