<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
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
        return view('portal.index');
    }  
               
}
