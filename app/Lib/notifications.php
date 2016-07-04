<?php 
namespace App\Lib;

use App\Models\Message;
use App\Models\UserComplain;
use App\Models\UserDocumentation;
use App\Models\UserTransactions;
use Config, Request, Route, Session;
use DB;

/**
* BetConstruct - Helper class to connect to BetConstruct API
* 
* 
* @package    App
* @subpackage Lib
*/


class Notifications {


    static function getMensagens()
    {
        $mensagens = Message::leftJoin('users', 'users.id', '=', 'users.id')
            //->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            //->leftJoin('transactions', 'transactions.id', '=', 'user_transactions.transaction_id')
            ->select(
                'text'

            )->where('viewed', '=', 0);



        return $mensagens->count();

    }
    
    static function getDocs()
    {
        $withdrawals = UserDocumentation::leftJoin('users', 'users.id', '=', 'user_documentation.user_id')
            //->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            //->leftJoin('transactions', 'transactions.id', '=', 'user_transactions.transaction_id')
            ->select(

                'status_id'
            )
            ->where('status_id', '=', 'pending');
        $levantamentos = $withdrawals->count();

        return $levantamentos;



    }

    static function getComplains()
    {
        $withdrawals = UserComplain::leftJoin('users', 'users.id', '=', 'user_complains.user_id')
            //->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            //->leftJoin('transactions', 'transactions.id', '=', 'user_transactions.transaction_id')
            ->select(

                'result'
            )
            ->where('result', '=', 'pending');
        $levantamentos = $withdrawals->count();

        return $levantamentos;

    }



}