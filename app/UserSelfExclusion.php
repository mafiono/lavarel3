<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property  user_session_id
 * @property string request_date
 * @property  user_id
 * @property  self_exclusion_type_id
 * @property  status
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
     * @param $id
     * @return UserSelfExclusion
     */
    public static function getCurrent($id)
    {
        $model = static::query()
            ->where('user_id', '=', $id)
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
            case 'undetermined_period':
                $selfExclusion->end_date = null;
                break;
            default:
                return false;
        }
        $selfExclusion->self_exclusion_type_id = $data['self_exclusion_type'];

        return $selfExclusion->save();
    }
}
