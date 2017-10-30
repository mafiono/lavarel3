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
use Log;

class CasinoGameController extends Controller
{
    public function index($id)
    {
        try {

            $game = CasinoGame::findOrFail($id);

            $user = Auth::user();

            $userSession = $user->logUserSession('new_game_session',
                "Create Session for Game $game->id: $game->name");

            $token = CasinoToken::create([
                'user_id' => $user->id,
                'user_session_id' => $userSession->id,
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
                    'operator' => 'casinoportugal',
                    'game_id' => $id,
                    'sessionstatus' => 'active',
                    'time_start' => Carbon::now(),
                    'balance_start' => $user->balance->balance_available * 100,
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Saving Session for user: $user->id -> $e->getMessage()");
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

    public function close($tokenId)
    {
        $userId = CasinoToken::whereTokenid($tokenId)
            ->first()
            ->user_id;

        (new Netent())->logoutUser($userId);
    }

    public function netentPlugin($tokenId)
    {
        return view('casino.netent_plugin', compact('tokenId'));
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
