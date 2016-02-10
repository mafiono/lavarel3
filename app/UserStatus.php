<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserStatus extends Model
{
    protected $table = 'user_statuses';
    
  /**
    * Relation with User
    *
    */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

  /**
    * Relation with Status
    *
    */
    public function status()
    {
        return $this->belongsTo('App\Status', 'status_id', 'id');
    }
    /**
     * Creates a new user status
     *
     * @param $status
     * @param string $type
     * @param $userId
     * @param $userSessionId
     *
     * @return object UserStatus
     */
    public function setStatus($status, $type = 'status_id', $userId, $userSessionId)
    {
        // TODO BUG
        // Get current user Status
        $userStatus = $this->query()->where('user_id', '=', $userId)->where('current', '=', 1)->first();
        if ($userStatus == null){
            $userStatus = new UserStatus;
            $userStatus->user_id = $userId;
            $userStatus->status_id = 'waiting_confirmation';
            $userStatus->current = 1;
        } else {
            // force to save a new value in DB
            $userStatus->id = null;
            $userStatus->exists = false;
        }
        /* Set all user status to false */
        $this->where('user_id', '=', $userId)
             ->update(['current' => '0']);

        switch ($type) {
            case 'identity_status_id': $userStatus->identity_status_id = $status; break;
            case 'email_status_id': $userStatus->email_status_id = $status; break;
            case 'address_status_id': $userStatus->address_status_id = $status; break;
            case 'iban_status_id': $userStatus->iban_status_id = $status; break;
            case 'status_id':
            default: $userStatus->status_id = $status; break;
        }
        $userStatus->user_session_id = $userSessionId;
        if ($userStatus->identity_status_id === 'confirmed'
            && $userStatus->email_status_id === 'confirmed'
            // TODO confirmar com o luis o Status ID se pode alterar
            && $userStatus->status_id !== 'active'
        ) {
            $userStatus->status_id = 'active';
        }

        if (!$userStatus->save())
        	return false;

        return $userStatus;
    }

}
