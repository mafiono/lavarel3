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
        $minse = $selfExclusion->request_date->Copy()->addDays(90);
        $endsr = $userRevocation->request_date->Copy()->addDays(30);

        if ($selfExclusion->self_exclusion_type_id !== 'reflection_period') {
            $status_code = $selfExclusion->self_exclusion_type_id === 'undetermined_period' ? 18 : 19;
            $action_code = 10;
            $description = $selfExclusion->self_exclusion_type_id === 'undetermined_period' ? 'Revogação de Auto Exclusão por tempo indeterminado' : 'Revogação de Auto Exclusão por tempo determinado';
            $start_date = $userRevocation->request_date;
            $original_date = $selfExclusion->request_date;
            if ($minse > $endsr) {
                $end_date = $minse;
                $duration = $minse->Copy()->diffInDays(Carbon::now()->subDay());
            } else {
                $end_date = $endsr;
                $duration = $endsr->Copy()->diffInDays(Carbon::now()->subDay());
            }
            $userRevocation->status_id = 'pending';
        } else {
            $status_code = 29;
            $action_code = 31;
            $description = 'Reativação Pausa';
            $start_date = $userRevocation->request_date;
            $end_date = null;
            $original_date = null;
            $userRevocation->status_id = 'processed';
        }

        $log = UserProfileLog::createLog($userId, true);
        $log->status_code = $status_code;
        $log->action_code = $action_code;
        $log->descr_acao = $description;
        $log->duration = $duration;
        $log->start_date = $start_date;
        $log->end_date = $end_date;
        $log->original_date = $original_date;
        $log->save();

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
