<?php
namespace App\Http\Controllers\Casino;

use App\Http\Controllers\Controller;
use App\Models\CasinoGame;
use View, Auth;
use Illuminate\Http\Request;

class CasinoController extends Controller
{
    public function __construct(Request $request)
    {
        View::share('authUser', Auth::user(), 'request', $request);
    }

    public function index()
    {
        $casino = true;

        $games = CasinoGame::whereEnabled(true)
            ->whereMobile(false)
            ->latest('is_new')
            ->get();

        return view('casino.index', compact('games', 'casino'));
    }

}