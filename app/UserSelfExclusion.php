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
            ->where('end_date', 'IS NULL')
            ->orWhere('end_date', '<', Carbon::now()->toDateTimeString())
            ->getModel();

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
        $selfExclusion->self_exclusion_type_id = $data['self_exclusion_type'];
        $selfExclusion->request_date = Carbon::now()->toDateTimeString();
        $selfExclusion->end_date = empty($data['dias']) ? null : Carbon::now()->addDay($data['dias']);

        return $selfExclusion->save();
    }
}
