<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Session, View, Response, Auth, Mail, Validator;
use Illuminate\Http\Request;
use App\JogadorConta;

class FriendsNetworkController extends Controller
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
        $this->middleware('auth');
        $this->request = $request;
        $this->authUser = Auth::user();
        $this->userSessionId = Session::get('user_session');

        View::share('authUser', $this->authUser, 'request', $request);        
    }

    /**
     * Display amigos/convites page
     *
     * @return \View
     */
    public function invitesGet()
    {
        return view('portal.friends.invites');
    }
    /**
     * Display amigos/rede page
     *
     * @return \View
     */
    public function network()
    {
        return view('portal.friends.network');
    }
}
