<?php
namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\UserBet;
use App\UserBetEvent;
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
        $this->middleware('auth', ['except' => ['index', 'sports', 'loadPost']]);
        $this->request = $request;
        $this->authUser = Auth::user();
        $this->userSessionId = Session::get('user_session');
        View::share('authUser', $this->authUser, 'request', $request);
    }

    public function sports()
    {
        $phpAuthUser = $this->authUser?[
            "id" => $this->authUser->id,
            "auth_token" => $this->authUser->api_password
        ]:null;
        return view('portal.bets.sports', ["phpAuthUser" => $phpAuthUser]);
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
