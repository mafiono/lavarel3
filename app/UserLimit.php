<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int user_id
 * @property int user_session_id
 * @property float limit_deposit_daily
 * @property float limit_deposit_weekly
 * @property float limit_deposit_monthly
 * @property float limit_betting_daily
 * @property float limit_betting_weekly
 * @property float limit_betting_monthly
 */
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
    * @param string $typeLimits 'Bets' or 'Deposits'
    *
    * @return boolean true or false
    */
    public static function changeLimits($data, $typeLimits, $userId, $userSessionId)
    {
        $limits = self::where('user_id', '=', $userId)->first();
        if (!$limits)
            $limits = new UserLimit;

        switch (strtolower($typeLimits)){
            case 'deposits':
                $limits->limit_deposit_daily = UserLimit::GetValueOrNull('limit_daily', $data);
                $limits->limit_deposit_weekly = UserLimit::GetValueOrNull('limit_weekly', $data);
                $limits->limit_deposit_monthly = UserLimit::GetValueOrNull('limit_monthly', $data);
                break;
            case 'bets':
                $limits->limit_betting_daily = UserLimit::GetValueOrNull('limit_daily', $data);
                $limits->limit_betting_weekly = UserLimit::GetValueOrNull('limit_weekly', $data);
                $limits->limit_betting_monthly = UserLimit::GetValueOrNull('limit_monthly', $data);
                break;
        }

        $limits->user_session_id = $userSessionId;
        $limits->user_id = $userId;

        return $limits->save();
    }    

    public static function GetValueOrNull($key, $array){
        return (array_key_exists($key, $array)? $array[$key]: null);
    }
}
