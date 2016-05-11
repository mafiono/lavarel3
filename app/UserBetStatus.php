<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserBetStatus
 * @package App
 */
class UserBetStatus extends Model
{
    protected $table = 'user_bet_statuses';

  /**
    * Relation with UserBet
    *
    */
    public function bet()
    {
        return $this->belongsTo('App\UserBet', 'user_bet_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function transaction()
    {
        return $this->hasOne('App\UserBetTransaction', 'user_bet_status_id', 'id');
    }

  /**
    * Creates a new user status
    *
    * @param array data
    *
    * @return object UserStatus
    */
    public function setStatus($status, $userBetId, $userSessionId) 
    {
        /* Set all user status to false */
        $this->where('user_bet_id', '=', $userBetId)
             ->update(['current' => '0']);
                             
        $userStatus = new UserBetStatus;
        $userStatus->user_bet_id = $userBetId;
        $userStatus->status_id = $status;
        $userStatus->current = 1;
        $userStatus->user_session_id = $userSessionId;
        
        if (!$userStatus->save())
        	return false;

        return $userStatus;
    }

    /**
     * @param UserBet $userBet
     * @return UserBetStatus
     */
    public static function setBetStatus(UserBet $userBet) {
        self::where('user_bet_id', $userBet->id)
            ->update(['current' => '0']);

        $betStatus = new static();
        $betStatus->user_bet_id = $userBet->id;
        $betStatus->status = $userBet->status;
        $betStatus->save();

        return $betStatus;
    }
}
