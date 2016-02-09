<?php
namespace App\Http\Controllers\Portal;

use App\CasinoGameTypes;
use App\Http\Controllers\Controller;
use Session, View, Response, Auth;
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

    /**
     * Main casino view
     * @return View
     */
    public function casino() {
        return view('portal.casino.casino');
    }

    /**
     * Get casino game types
     * @return \Illuminate\Http\JsonResponse
     */
    public function gameTypes() {
        return Response::json(["game_types" => CasinoGameTypes::orderBy("position")->get(["id", "name", "css_icon"])]);
    }

    public function gameTypeList() {
        return Response::json(["game_types" => CasinoGameTypes::orderBy("position")->get(["id", "name", "css_icon"])]);
    }
}