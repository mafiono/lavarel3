<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
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
        if ($selfExclusion == null || empty($selfExclusion) || empty($selfExclusion->id)) {
            return false;
        }
        $userRevocation = new UserRevocation();
        $userRevocation->user_id = $userId;
        $userRevocation->self_exclusion_id = $selfExclusion->id;
        $userRevocation->user_session_id = $userSessionId;
        $userRevocation->request_date = Carbon::now()->toDateTimeString();

//calculo do fim da revogação
        $minse = $selfExclusion->request_date->addMonth(3);
        $endsr = $userRevocation->request_date->addDays(30);


        $log = UserProfileLog::createLog($userId);
        $log->status_code = $selfExclusion->self_exclusion_type_id === 'undetermined_period' ? 18 : 19;
        $log->action_code = 10;
        $log->start_date = $userRevocation->request_date;
        $log->original_date = $selfExclusion->request_date;
        if ($minse > $endsr) {
            $log->end_date = $minse;
        } else {
            $log->end_date = $endsr;
        }


        if ($selfExclusion->self_exclusion_type_id === 'reflection_period') {
            $log->status_code = 29;
            $log->action_code = 31;
            $log->start_date = $userRevocation->request_date;
            $log->end_date = null;
            $log->original_date = null;
            $log->save();
            $userRevocation->status_id = 'processed';
        } else {
            $userRevocation->status_id = 'pending';
        }

        return $userRevocation->save() ? $userRevocation : false;
    }

    /**
     * Cancel a Revocation
     *
     * @return bool
     */
    public function cancelRevoke()
    {
        if ($this->status_id !== 'pending') {
            return false;
        }

        $this->status_id = 'canceled';
        $this->user_session_id = UserSession::getSessionId();

        return $this->save();
    }

    /**
     * Process a Revocation
     *
     * @return bool
     */
    public function processRevoke()
    {
        if ($this->status_id !== 'pending') {
            return false;
        }

        $this->status_id = 'processed';
        $this->user_session_id = UserSession::getSessionId();

        return $this->save();
    }
}
