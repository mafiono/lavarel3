<?php
namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Session, View, Response, Auth, Mail, Validator;
use Illuminate\Http\Request;

class CasinoController extends Controller {
    protected $authUser;
    protected $request;
    protected $userSessionId;

    public function __construct(Request $request) {
        //$this->middleware('auth', ['except' => ['index', 'sports', 'loadPost']]);
        $this->request = $request;
        $this->authUser = Auth::user();
        $this->userSessionId = Session::get('userSessionId');
        View::share('authUser', $this->authUser, 'request', $request);
    }

    public function casino() {
        return view('portal.casino.casino');
    }

    public function gameTypes() {
//        return
    }
}