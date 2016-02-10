<?php

namespace App\Http\Controllers;

use View, Datatable;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Auth;

class DashboardController extends Controller {

    protected $authUser;

    protected $request;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        //$this->middleware('auth');
        $this->request = $request;
        $this->authUser = Auth::user();

        View::share('authUser', $this->authUser, 'request', $request);        
    }

    /**
     * Display dashboard index page
     *
     * @return \View
     */
    public function index()
    {
        return view('dashboard.index');
    }
}
