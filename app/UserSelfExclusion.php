<?php

namespace App;

use Auth;
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
        $model = static::query()
            ->where('user_id', '=', $id)
            ->where('status', '=', 'active')
            ->where(function($query){
                $query
                    ->where('end_date', 'IS NULL')
                    ->orWhere('end_date', '>', Carbon::now()->toDateTimeString());
            })
            ->first();

        return $model;
    }
  /**
    * Create a new User Self Exclusion Request
    *
    * @param array data
    *
    * @return boolean true or false
    */
    public static function selfExclusionRequest($data, $userId)
    {
        if (empty($data['self_exclusion_type']))
            return false;
        $typeId = $data['self_exclusion_type'];
        $motive = empty($data['motive']) ? null : $data['motive'];
        if ($typeId !== 'reflection_period')
            if (empty($data['motive']) || strlen($data['motive']) < 5)
                return false;
            else
                $motive = $data['motive'];

        $selfExclusion = new UserSelfExclusion();
        $selfExclusion->user_id = $userId;
        $selfExclusion->user_session_id = UserSession::getSessionId();
        // novos self exclusion ficam activos imediatamente
        $selfExclusion->status = 'active';
        $selfExclusion->request_date = Carbon::now()->toDateTimeString();
        $selfExclusion->motive = $motive;
        switch ($typeId){
            case '1year_period':
                $selfExclusion->end_date = Carbon::now()->addYears(1);
                break;
            case '3months_period':
                $selfExclusion->end_date = Carbon::now()->addMonths(3);
                break;
            case 'minimum_period':
                if (empty($data['dias'])) return false;
                if ($data['dias'] < 90) return false;
                $selfExclusion->end_date = Carbon::now()->addDays($data['dias']);
                break;
            case 'reflection_period':
                if (empty($data['dias'])) return false;
                if ($data['dias'] < 1) return false;
                if ($data['dias'] > 90) return false;
                $selfExclusion->end_date = Carbon::now()->addDays($data['dias']);
                break;
            case 'undetermined_period':
                $selfExclusion->end_date = null;
                break;
            default:
                return false;
        }
        $selfExclusion->self_exclusion_type_id = $typeId;

        return $selfExclusion->save() ? $selfExclusion : false;
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
     * @return UserSelfExclusion
     */
    public static function createFromSRIJ($selfExclusionSRIJ)
    {
        $se = new UserSelfExclusion;
        $se->status = 'active';
        $se->user_id = Auth::id();
        $se->user_session_id = UserSession::getSessionId();
        $se->request_date = $selfExclusionSRIJ->start_date;
        $se->end_date = $selfExclusionSRIJ->end_date;
        $se->self_exclusion_type_id = 'minimum_period';

        $se->save();
        return $se;
    }
    /**
     * Update current user and create a new
     *
     * @param ListSelfExclusion $selfExclusionSRIJ
     * @return UserSelfExclusion
     */
    public function updateWithSRIJ($selfExclusionSRIJ)
    {
        $this->status = 'canceled';
        $this->save();

        $this->id = null;
        $this->exists = false;
        $this->request_date = $selfExclusionSRIJ->start_date;
        $this->end_date = $selfExclusionSRIJ->end_date;

        $this->save();
        return $this;
    }
}
