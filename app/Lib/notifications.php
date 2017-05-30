<?php
namespace App\Lib;

use App\Models\Message;
use App\Models\UserComplain;
use App\Models\UserDocumentation;
use App\Models\UserTransactions;
use Config, Request, Route, Session;
use DB;
use Illuminate\Support\Facades\Auth;

/**
 * BetConstruct - Helper class to connect to BetConstruct API
 *
 *
 * @package    App
 * @subpackage Lib
 */
class Notifications
{
    static function getMensagens()
    {
        $mensagens = Message::query()
            ->where('user_id', '=', Auth::user()->id)
            ->where('viewed', '=', 0)
            ->where('sender_id', '!=', Auth::user()->id);

        return $mensagens->count();
    }

    static function getTotalMensagens()
    {
        $mensagens = Message::query()
            ->where('user_id', '=', Auth::user()->id);

        return $mensagens->count();
    }

}