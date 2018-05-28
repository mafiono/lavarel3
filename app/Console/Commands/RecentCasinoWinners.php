<?php

namespace App\Console\Commands;

use Anchu\Ftp\Facades\Ftp;
use App\CasinoTransaction;
use App\GlobalSettings;
use App\Models\CasinoGame;
use App\User;
use App\UserBet;
use App\UserBetTransaction;
use App\UserTransaction;
use Cache;
use Carbon\Carbon;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class RecentCasinoWinners extends Command
{
    protected $signature = 'recent-winners {--debug}';

    protected $description = 'Gera a lista dos ultimos Winners';


    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $debug = $this->option('debug');
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
        $list = $query
            ->get()
            ->map(function ($win) {
                return [
                        'amount' => number_format($win->totalAmount, 0, '.', ' ') . '€',
                        'username' => '***' . substr($win->username, 3),
                    ] + $win->toArray();
            });
        Cache::put('recent-winners', $list, 60);
        if ($debug) {
            dd($list);
        }
    }
}
