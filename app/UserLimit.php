<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLimit extends Model
{
    protected $table = 'user_limits';

  /**
    * Relation with User
    *
    */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }  

  /**
    * Changes an user limits
    *
    * @param array data
    *
    * @return boolean true or false
    */
    public static function changeLimits($data, $userId, $userSessionId) 
    {
        $limits = self::where('user_id', '=', $userId)->first();
        if (!$limits)
            $limits = new UserLimit;

        foreach ($data as $key => $value) {
            $limits->$key = $value;
        }

        $limits->user_session_id = $userSessionId;
        $limits->user_id = $userId;

        return $limits->save();
    }    

}
