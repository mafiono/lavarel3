<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int user_id
 * @property int user_session_id
 * @property string self_exclusion_type_id
 * @property string request_date
 * @property Carbon end_date
 * @property string status
 */
class UserSelfExclusion extends Model
{
    protected $table = 'user_self_exclusions';
    protected $dates = ['end_date', 'start_date'];

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
    public static function selfExclusionRequest($data, $userId, $userSessionId) 
    {
        if (empty($data['self_exclusion_type']))
            return false;

        $selfExclusion = new UserSelfExclusion();
        $selfExclusion->user_id = $userId;
        $selfExclusion->user_session_id = $userSessionId;
        // novos self exclusion ficam activos imediatamente
        $selfExclusion->status = 'active';
        $selfExclusion->request_date = Carbon::now()->toDateTimeString();
        switch ($data['self_exclusion_type']){
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
        $selfExclusion->self_exclusion_type_id = $data['self_exclusion_type'];

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
}
