<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBetStatus extends Model
{
    protected $table = 'user_bet_statuses';
    
  /**
    * Relation with UserBet
    *
    */
    public function userBet()
    {
        return $this->belongsTo('App\UserBet', 'user_bet_id', 'id');
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

}
