<?php

namespace App\Http\Controllers\Portal;

use App\Models\Message;
use Illuminate\Routing\Controller;
use Auth;


class BalanceController extends Controller
{

    public function balance()
    {
        if (!Auth::check())
            return [];

        $user = Auth::user();

        $balance = [];
        $balance['balance'] = $user->balance->balance_available;
        $balance['bonus'] = $user->balance->balance_bonus;
        $balance['total'] = $user->balance->balance_total;

        $balance = array_map(function ($value) {
            return number_format($value, 2, '.', ',');
        }, $balance);

        $mensagens = Message::query()
            ->where('user_id', '=', $user->id)
            ->where('viewed', '=', 0)
            ->where('sender_id', '!=', $user->id);

        $balance['unreads'] = $mensagens->count();

        return response()->json($balance);
    }

}

