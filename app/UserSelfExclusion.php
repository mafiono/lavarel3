<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
    * Create a new User Self Exclusion Request
    *
    * @param array data
    *
    * @return boolean true or false
    */
    public static function selfExclusionRequest($data, $userId, $userSessionId) 
    {
        if (empty($data['status']) || empty($data['self_exclusion_type']))
            return false;

        $selfExclusion = new UserSelfExclusion();
        $selfExclusion->status = $data['status'];
        $selfExclusion->self_exclusion_type_id = $data['self_exclusion_type'];
        $selfExclusion['user_id'] = $userId;
        $selfExclusion['request_date'] = \Carbon\Carbon::now()->toDateTimeString();
        $selfExclusion->user_session_id = $userSessionId;

        return $selfExclusion->save();
    }
}
