<?php

namespace App;

use Session;
use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    protected $table = 'user_sessions';
    
  /**
    * Relation with Jogador
    *
    */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }               

  /**
    * Creates a new user session
    *
    * @param array data
    *
    * @return object UserSession
    */
    public static function createSession($userId, $data = []) 
    {
        $sessionNumber = Session::get('user_session_number', null);
        if ($sessionNumber == null) {
            $sessionNumber = self::where('user_id', '=', $userId)
                ->max('session_number');

            if (!$sessionNumber)
                $sessionNumber = 1;
            else
                $sessionNumber += 1;

            Session::put("user_session_number", $sessionNumber);
        }

        $newSession = new UserSession;
        $newSession->user_id = $userId;
        $newSession->session_number = $sessionNumber;
        $newSession->session_id = "u".$userId."n".$sessionNumber."s".Session::getId();
        $newSession->description = !empty($data['description']) ? $data['description'] : '';
        $newSession->ip = \Request::getClientIp();

        if (!$newSession->save())
        	return false;

        return $newSession;
    }

    public static function findBySessionId($sid) {
        return self::where("session_id", "=", $sid)->first();
    }
}
