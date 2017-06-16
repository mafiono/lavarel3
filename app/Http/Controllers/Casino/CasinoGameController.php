<?php

namespace App\Http\Controllers\Casino;

use App\Models\CasinoSession;
use App\Models\CasinoToken;
use App\Models\CasinoGame;
use App\Http\Controllers\Controller;
use App\Netent\Netent;
use App\UserSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CasinoGameController extends Controller
{
    public function index($id)
    {
        $game = CasinoGame::findOrFail($id);

        $user = Auth::user();

        $token = CasinoToken::create([
            'user_id' => $user->id,
            'user_session_id' => UserSession::getSessionId(),
            'tokenid' => str_random(32),
            'used' => 0
        ]);

        if ($game->provider === 'netent') {
            $sessionId = (new Netent())->loginUserDetailed($user->id);

            CasinoSession::create([
                'sessionid' => $sessionId,
                'user_id' => $user->id,
                'token_id' => $token->id,
                'country' => 'PT',
                'operator' => 'casinoportugal',
                'game_id' => $id,
                'sessionstatus' => 'active',
                'time_start' => Carbon::now(),
                'balance_start' => $user->balance->balance_available * 100,
            ]);

            $url = '/netent-game.php'
                . '?gameId=' . $game->id
                . '&staticServer=' . config('app.netent_static_server')
                . '&gameServer=' . config('app.netent_game_server')
                . '&sessionId=' . $sessionId;

            $url = 'https://casinoportugal-static-test.casinomodule.com/games/'
                . $game->prefix . '/game/' . $game->prefix
                . '.xhtml?lobbyURL=https://casinoportugal-admin-test.casinomodule.com/admin/tester.jsp/'
                . '&server=https://casinoportugal-game-test.casinomodule.com/'
                . '&operatorId=casinoportugal&gameId=' . $id . '&lang=en&sessId=' . $sessionId;
        } else {
            $url = config('app.isoftbet_launcher') . "{$game->prefix}{$game->id}?lang=pt&cur=EUR&mode=1&background=1&uid={$user->id}&user={$user->username}&token={$token->tokenid}&lobbyURL=".config('app.casino_lobby');
        }

        return view('casino.game', compact('game', 'url', 'sessionId'));
    }
}
