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
            $netent = new Netent();

            $netent->logoutUser($user->id);

            $sessionId = $netent->loginUserDetailed(
                $user->id,
                $game->mobile ? ['channel', 'mobg'] : []
            );

            CasinoSession::create([
                'provider' => $game->provider,
                'sessionid' => $sessionId,
                'user_id' => $user->id,
                'token_id' => $token->id,
                'country' => 'PT',
                'operator' => 0,
                'game_id' => $id,
                'sessionstatus' => 'inactive',
                'time_start' => Carbon::now(),
                'initial_balance' => $user->balance->balance_available,
                'initial_bonus' => $user->balance->balance_bonus,
            ]);
        }

        return view('casino.game', compact('user', 'game', 'token', 'sessionId'));
    }

    public function report($token)
    {
        $token = CasinoToken::whereTokenid($token)
            ->has('sessions.rounds.transactions')
            ->first();
        $total = number_format(
            $token ? $token->sessions->reduce(function ($carry, $session) {
                return $carry + $this->sumSessionAmounts($session);
            }) : 0
            , 2
        );

        return view('casino.game_report', compact('token', 'total'));
    }

    public function demo($id)
    {
        $game = CasinoGame::findOrFail($id);

        return view('casino.game_demo', compact('game'));
    }

    protected function sumSessionAmounts($session)
    {
        return $session->rounds->reduce(function ($carry, $round) {
            return $carry
                - $round->transactions->where('type', 'bet')->sum('amount')
                - $round->transactions->where('type', 'bet')->sum('amount_bonus')
                + $round->transactions->where('type', 'win')->sum('amount')
                + $round->transactions->where('type', 'win')->sum('amount_bonus');
        });
    }
}
