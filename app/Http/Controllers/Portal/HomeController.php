<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Highlight;
use View, Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    protected $authUser;

    protected $request;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->authUser = Auth::user();
        View::share('authUser', $this->authUser, 'request', $request);        
    }
    /**
     * Display homepage
     *
     * @return \View
     */
    public function index()
    {
        $phpAuthUser = $this->authUser?[
            "id" => $this->authUser->id,
            "auth_token" => $this->authUser->api_password
        ]:null;

        $competitions = Highlight::competitions()->get(['highlight_id']);

        return view('portal.index',  compact("phpAuthUser", "competitions"));
    }
}
