<?php

namespace App\Http\Controllers\Casino;

use App\CasinoTransaction;
use App\Http\Controllers\Controller;
use App\Lib\DebugQuery;
use App\Models\CasinoGame;
use App\User;
use DB;

class CasinoRecentWinnersController extends Controller
{
    public function index()
    {
        $query = CasinoTransaction::from(CasinoTransaction::alias('ct'))
            ->leftJoin(User::alias('u'), 'u.id', '=', 'ct.user_id')
            ->leftJoin(CasinoGame::alias('g'), 'g.id', '=', 'ct.game_id')
            ->select([
                'g.name',
                'g.description',
                'g.prefix',
                'g.desktop',
                'g.mobile',
                'g.image',
                'ct.user_id',
                'u.username',
                'ct.session_id',
                'ct.game_id',
                DB::raw('SUM(CASE ' .
                    'WHEN ct.type = \'win\' '.
                    'THEN ct.amount + ct.amount_bonus '.
                    'ELSE (ct.amount + ct.amount_bonus) * -1 '.
                    'END) as totalAmount'),
            ])
            ->where('g.enabled', '=', true)
            ->where('g.suspended', '=', false)
            ->latest('ct.session_id')
            ->groupBy('ct.session_id')
            ->take(10)
            ->having('totalAmount', '>', 100)
            ;
//        DebugQuery::make($query, true);
        return $query
            ->get()
            ->map(function ($win) {
                return [
                    'amount' => number_format($win->totalAmount, 0, '.', ' ') . '€',
                    'username' => '***' . substr($win->username, 3),
                ] + $win->toArray();
            });
    }
}
