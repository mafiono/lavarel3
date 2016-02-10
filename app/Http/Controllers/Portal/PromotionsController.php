<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Session, View, Response, Auth, Mail, Validator;
use Illuminate\Http\Request;

class PromotionsController extends Controller
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
        $this->userSessionId = Session::get('userSessionId');

        View::share('authUser', $this->authUser, 'request', $request);        
    }
    /**
     * Display index page
     *
     * @return \View
     */
    public function index()
    {
        return view('portal.promotions.index');
    }

    /**
     * Display pendentes page
     *
     * @return \View
     */
    public function pendents()
    {
        return view('portal.promotions.pendents');
    }
    /**
     * Display utilizados page
     *
     * @return \View
     */
    public function used()
    {
        return view('portal.promotions.used');
    }
}
