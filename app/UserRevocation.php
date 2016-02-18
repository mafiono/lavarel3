<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int user_id
 * @property int user_session_id
 * @property int self_exclusion_id
 * @property Carbon request_date
 * @property string status_id
 */
class UserRevocation extends Model
{
    protected $table = 'user_revocations';
    protected $dates = ['request_date'];

    /**
    * Relation with User
    *
    */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /**
     * Create a new User Revocation to a SelfExclusion Request
     *
     * @param $userId
     * @param UserSelfExclusion $selfExclusion
     * @param $userSessionId
     * @return bool true or false
     */
    public static function requestRevoke($userId, UserSelfExclusion $selfExclusion, $userSessionId)
    {
        if ($selfExclusion == null || empty($selfExclusion) || empty($selfExclusion->id)){
            return false;
        }

        $userRevocation = new UserRevocation();
        $userRevocation->user_id = $userId;
        $userRevocation->self_exclusion_id = $selfExclusion->id;
        $userRevocation->user_session_id = $userSessionId;
        $userRevocation->request_date = Carbon::now()->toDateTimeString();

        if ($selfExclusion->self_exclusion_type_id === 'reflection_period'){
            $userRevocation->status_id = 'processed';
        } else {
            $userRevocation->status_id = 'pending';
        }

        return $userRevocation->save() ? $userRevocation : false;
    }

    /**
     * Cancel a Revocation
     *
     * @param $userSessionId
     * @return bool
     */
    public function cancelRevoke($userSessionId)
    {
        if ($this->status_id !== 'pending') {
            return false;
        }

        $this->status_id = 'canceled';
        $this->user_session_id = $userSessionId;

        return $this->save();
    }
}
