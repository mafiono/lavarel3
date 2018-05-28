<?php

namespace App\Http\Controllers\Casino;

use App\Models\CasinoGame;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class CasinoFavoritesController extends Controller
{
    public function index()
    {
        $query=CasinoGame::query()
            ->where('enabled','=',1)
            ->favorited()->get();
        return $query;
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
