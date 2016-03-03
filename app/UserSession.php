<?php

namespace App;

use Auth;
use Session;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string ip
 * @property string description
 * @property string session_id
 * @property int|mixed session_number
 * @property int user_id
 */
class UserSession extends Model {
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
     * @param $userId
     * @param array $data data
     * @param bool $newSession
     *
     * @return object UserSession
     */
    public static function createSession($userId, $data = [], $newSession = false)
    {
        $sessionNumber = Session::get('user_session_number', null);
        if ($sessionNumber == null || $newSession) {
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

        Session::put('user_session', $newSession->id);

        return $newSession;
    }

    public static function getSessionId(){
        $sessionId = Session::get('user_session', null);
        if ($sessionId != null)
            return $sessionId;

        $userId = Auth::id();
        if ($userId == null)
            throw new \Exception("User not logged!");

        if (! $session = self::createSession($userId, ['description' => 'create new session']))
            throw new \Exception("Fail create Session!");
        return $session->id;
    }

    public static function findBySessionId($sid) {
        return self::where("session_id", "=", $sid)->first();
    }
}
