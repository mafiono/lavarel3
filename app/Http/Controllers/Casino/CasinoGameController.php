<?php

namespace App\Http\Controllers\Casino;

use App\Models\CasinoToken;
use App\Models\CasinoGame;
use App\Http\Controllers\Controller;
use App\UserSession;
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

        return view('casino.game', compact('game', 'user', 'token'));
    }

    public function report($token)
    {
        $token = CasinoToken::whereTokenid($token)
            ->has('sessions.rounds.transactions')
            ->first();
        $total = number_format(
            $token ? $token->sessions->reduce(function ($carry, $session) {
                return $carry + $this->sumSessionAmounts($session);
            })/100 : 0
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
