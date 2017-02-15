<?php

namespace App\Http\Controllers\Casino;

use App\Models\CasinoGame;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class CasinoFavoritesController extends Controller
{
    public function index()
    {
        return CasinoGame::favorited()->get();
    }

    public function store(Request $request)
    {

        CasinoGame::find($request->id)->setFavorite();
    }

    public function destroy($id)
    {
        CasinoGame::find($id)->unsetFavorite();
    }
}
