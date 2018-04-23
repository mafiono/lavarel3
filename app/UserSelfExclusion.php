<?php

namespace App;

use App\Exceptions\SelfExclusionException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int user_id
 * @property int user_session_id
 * @property string self_exclusion_type_id
 * @property string motive
 * @property Carbon request_date
 * @property Carbon end_date
 * @property string status
 */
class UserSelfExclusion extends Model
{
    protected $table = 'user_self_exclusions';
    protected $dates = ['end_date', 'request_date'];

    /**
     * Relation with User
     *
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /**
     * Has this SelfExclusion any pending Revocation?
     *
     * @return UserRevocation
     */
    public function hasRevocation()
    {
        return $this->hasMany('App\UserRevocation', 'self_exclusion_id', 'id')
            ->where('status_id', '=', 'pending')
            ->first();
    }
    /**
     * @param $id
     * @return UserSelfExclusion
     */
    public static function getCurrent($id)
    {
        /** @var UserSelfExclusion $model */
        $model = static::query()
            ->where('user_id', '=', $id)
            ->where('status', '=', 'active')
            ->where(function ($query) {
                $query
                    ->whereNull('end_date')
                    ->orWhere('end_date', '>', Carbon::now()->toDateTimeString());
            })
            ->first();

        return $model;
    }

    /**
     * Create a new User Self Exclusion Request
     *
     * @param $data
     * @param $userId
     * @return UserSelfExclusion
     * @throws SelfExclusionException
     */
    public static function selfExclusionRequest($data, $userId)
    {
        if (empty($data['self_exclusion_type']))
            throw new SelfExclusionException('select_self_exclusion_type', 'Selecione o tipo de autoexclusão!');
        $typeId = $data['self_exclusion_type'];
        $motive = empty($data['motive']) ? null : $data['motive'];
        if ($typeId !== 'reflection_period')
            if (empty($data['motive']) || strlen($data['motive']) < 5)
                throw new SelfExclusionException('missing_motive', 'Indique o motivo!');
            else
                $motive = $data['motive'];

        $selfExclusion = new UserSelfExclusion();
        $selfExclusion->user_id = $userId;
        $selfExclusion->user_session_id = UserSession::getSessionId();
        // novos self exclusion ficam activos imediatamente
        $selfExclusion->status = 'active';
        $selfExclusion->request_date = Carbon::now()->toDateTimeString();
        $selfExclusion->motive = $motive;
        $status_code = 10;
        $action_code = 11;
        $start_date = Carbon::now()->toDateTimeString();
        $descr_acao = "Autoexclusão por tempo determinado: ";
        $dateNow = Carbon::now();
        switch ($typeId) {
            case '1year_period':
                $end_date = $dateNow->copy()->addYears(1);
                $selfExclusion->end_date = $end_date;
                $descr_acao .= "1 Year";
                break;
            case '3months_period':
                $end_date = $dateNow->copy()->addMonths(3);
                $selfExclusion->end_date = $end_date;
                $descr_acao .= "3 Months";
                break;
            case 'minimum_period':
                if (empty($data['se_meses'])) throw new SelfExclusionException('missing_se_meses', 'Indique o nr de meses!');
                $months = $data['se_meses'];
                if ($months < 3) throw new SelfExclusionException('min_se_meses', 'Minimo de meses é 3!');
                if ($months > 999) throw new SelfExclusionException('max_se_meses', 'Máximo de meses é 999!');
                $end_date = $dateNow->copy()->addMonths($months);
                $selfExclusion->end_date = $end_date;
                $descr_acao .= "$months meses";
                break;
            case 'reflection_period':
                if (empty($data['rp_dias'])) throw new SelfExclusionException('missing_rp_dias', 'Indique o nr de dias!');
                $dias = $data['rp_dias'];
                if ($dias < 1) throw new SelfExclusionException('min_rp_dias', 'Minimo de dias é de 1!');
                if ($dias > 90) throw new SelfExclusionException('max_rp_dias', 'Máximo de dias é 90!');
                $end_date = $dateNow->copy()->addDays($dias);
                $selfExclusion->end_date = $end_date;
                $status_code = 20;
                $action_code = 31;
                $descr_acao = "Pausa de reflexão: $dias dias";
                break;
            case 'undetermined_period':
                $selfExclusion->end_date = null;
                $action_code = 10;
                $start_date = $dateNow;
                $end_date = null;
                $descr_acao = "Autoexclusão por tempo indeterminado";
                break;
            default:
                throw new SelfExclusionException('unknow_type', 'Tipo de autoexclusão desconhecido');
        }
        $selfExclusion->self_exclusion_type_id = $typeId;

        if (!$selfExclusion->save()) {
            throw new SelfExclusionException('fail_saving', 'Falha ao gravar os dados');
        }
        /** @var UserProfileLog $log */
        $log = UserProfileLog::createLog($userId, true);
        $log->status_code = $status_code;
        $log->action_code = $action_code;
        $log->motive = $selfExclusion->motive;
        $log->duration = $end_date === null ? 0 : $end_date->diffInDays();
        $log->descr_acao = $descr_acao;
        $log->start_date = $start_date;
        $log->end_date = $end_date;
        $log->save();

        return $selfExclusion;
    }

    /**
     * Revoke this Self-Exclusion
     *
     * @return boolean true or false
     */
    public function revoke()
    {
        if ($this->self_exclusion_type_id !== 'reflection_period')
            return false;

        if ($this->status !== 'active')
            return false;

        $this->status = 'canceled';

        return $this->save();
    }
    /**
     * Process this Self-Exclusion
     *
     * @return boolean true or false
     */
    public function process()
    {
        if ($this->status !== 'active')
            return false;

        $this->status = 'processed';

        return $this->save();
    }

    /**
     *
     * @param ListSelfExclusion $selfExclusionSRIJ
     * @param UserSession $userSession
     * @return UserSelfExclusion
     */
    public static function createFromSRIJ($selfExclusionSRIJ, $userSession)
    {
        $se = new UserSelfExclusion;
        $se->status = 'active';
        $se->user_id = $userSession->user_id;
        $se->user_session_id = $userSession->id;
        $se->request_date = $selfExclusionSRIJ->start_date;
        $se->end_date = $selfExclusionSRIJ->end_date;
        $se->self_exclusion_type_id = $selfExclusionSRIJ->end_date !== null ? 'minimum_period' : 'undetermined_period';

        $se->save();
        return $se;
    }

    /**
     * Update current user and create a new
     *
     * @param ListSelfExclusion $selfExclusionSRIJ
     * @param UserSession $userSession
     * @return UserSelfExclusion
     */
    public function updateWithSRIJ($selfExclusionSRIJ, $userSession)
    {
        $this->status = 'canceled';
        $this->save();

        $this->id = null;
        $this->exists = false;
        $this->status = 'active';
        $this->request_date = $selfExclusionSRIJ->start_date;
        $this->end_date = $selfExclusionSRIJ->end_date;
        $this->user_session_id = $userSession->id;

        $this->save();
        return $this;
    }
}
