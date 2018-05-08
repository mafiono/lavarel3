<?php

namespace App\Http\Controllers\Casino;

use App\Http\Controllers\Controller;
use Cache;

class CasinoRecentWinnersController extends Controller
{
    public function index()
    {
        if (Cache::has('recent-winners')) {
            return Cache::get('recent-winners');
        }
        return [];
    }
}
