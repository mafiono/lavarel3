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
        if ($selfExclusion->hasRevocation()) {
            return false;
        }

        //calculo do fim da revogação
        $dataAtual = Carbon::now();
        $minse = $selfExclusion->request_date->Copy()->addDays(90);
        $endsr = $dataAtual->copy()->addDays(30);
        $endse = $selfExclusion->end_date;
        $max = $minse->max($endsr);

        $create = null;

        if ($selfExclusion->self_exclusion_type_id !== 'reflection_period') {
            if ($endse === null || $endse > $max) {
                $status_code = $selfExclusion->self_exclusion_type_id === 'undetermined_period' ? 18 : 19;
                $action_code = 10;
                $description = $selfExclusion->self_exclusion_type_id === 'undetermined_period' ? 'Revogação de Auto Exclusão por tempo indeterminado' : 'Revogação de Auto Exclusão por tempo determinado';
                $original_date = $selfExclusion->request_date;
                $end_date = $max;
                $duration = $max->copy()->diffInDays(Carbon::now()->startOfDay());

                $revocationStatus = 'pending';
                $create = 1;
            }
        } else {
            $status_code = 29;
            $action_code = 31;
            $description = 'Reativação Pausa';
            $duration = 0;
            $end_date = null;
            $original_date = null;

            $revocationStatus = 'processed';
            $create = 1;
        }

        if ($create !== null) {
            $userRevocation = new UserRevocation();
            $userRevocation->user_id = $userId;
            $userRevocation->self_exclusion_id = $selfExclusion->id;
            $userRevocation->user_session_id = $userSessionId;
            $userRevocation->request_date = $dataAtual;
            $userRevocation->status_id = $revocationStatus;

            $log = UserProfileLog::createLog($userId, true);
            $log->status_code = $status_code;
            $log->action_code = $action_code;
            $log->descr_acao = $description;
            $log->duration = $duration;
            $log->start_date = $dataAtual;
            $log->end_date = $end_date;
            $log->original_date = $original_date;
            $log->save();
        } else {
            return false;
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
