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
}
