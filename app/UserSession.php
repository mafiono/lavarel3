<?php

namespace App;

use Auth;
use Request;
use Session;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string ip
 * @property string session_type
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
     * @param $type
     * @param $description
     * @param $userId int
     * @param bool $newSession
     *
     * @return object UserSession
     */
    public static function logSession($type, $description, $userId = null, $newSession = false){
        $userId = $userId ?: User::getCurrentId();
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
        $newSession->session_type = $type ?: 'log';
        $newSession->description = $description ?: '';
        $newSession->ip = Request::getClientIp();

        if (!$newSession->save())
            return false;

        Session::put('user_session', $newSession->id);

        return $newSession;
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
        $description = !empty($data['description']) ? $data['description'] : '';
        return self::logSession('undefined', $description, $userId, $newSession);
    }

    public static function getSessionId(){
        $sessionId = Session::get('user_session', null);
        if ($sessionId != null)
            return $sessionId;

        $userId = Auth::id();
        if ($userId == null)
            throw new \Exception("User not logged!");

        if (! $session = self::logSession('new_session', 'create new session', $userId))
            throw new \Exception("Fail create Session!");
        return $session->id;
    }

    public static function findBySessionId($sid) {
        return self::where("session_id", "=", $sid)->first();
    }
}
