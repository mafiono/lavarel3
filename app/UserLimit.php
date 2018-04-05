<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int user_id
 * @property int user_session_id
 * @property string limit_id
 * @property float limit_value
 * @property Carbon implement_at
 */
class UserLimit extends Model
{
    protected $table = 'user_limits';
    protected $dates = ['implement_at'];

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
    * @param int $userId
    * @param int $userSessionId
    * @param array $data
    * @param string $typeLimits 'Bets' or 'Deposits'
    *
    * @return boolean true or false
    */
    public static function changeLimits($userId, $userSessionId, $data, $typeLimits)
    {
        switch (strtolower($typeLimits)){
            case 'deposits':
                self::SetLimitFor($userId, $userSessionId, 'limit_deposit_daily', self::GetValueOrNull('limit_daily_deposit', $data));
                self::SetLimitFor($userId, $userSessionId, 'limit_deposit_weekly', self::GetValueOrNull('limit_weekly_deposit', $data));
                self::SetLimitFor($userId, $userSessionId, 'limit_deposit_monthly', self::GetValueOrNull('limit_monthly_deposit', $data));
                break;
            case 'bets':
                self::SetLimitFor($userId, $userSessionId, 'limit_betting_daily', self::GetValueOrNull('limit_daily_bet', $data));
                self::SetLimitFor($userId, $userSessionId, 'limit_betting_weekly', self::GetValueOrNull('limit_weekly_bet', $data));
                self::SetLimitFor($userId, $userSessionId, 'limit_betting_monthly', self::GetValueOrNull('limit_monthly_bet', $data));
                break;
        }
        return true;
    }

    /**
     * @param $userId
     * @param $typeId
     * @return UserLimit
     */
    public static function GetCurrLimitFor($userId, $typeId){
        /* @var $limit UserLimit */
        $limit = self::where('user_id', '=', $userId)
            ->where('limit_id', '=', $typeId)
            ->whereNotNull('implement_at')
            ->where('implement_at', '<=', Carbon::now()->toDateTimeString())
            ->orderBy('created_at', 'desc')
            ->first();

        return $limit;
    }

    /**
     * Get current Limit Value
     * @param $userId
     * @param $typeId
     * @return float|null
     */
    public static function GetCurrLimitValue($userId, $typeId){
        $limit = self::GetCurrLimitFor($userId, $typeId);
        return $limit != null? $limit->limit_value: null;
    }

    /**
     * @param $userId
     * @param $typeId
     * @return UserLimit
     */
    public static function GetLastLimitFor($userId, $typeId){
        /* @var $limit UserLimit */
        $limit = self::where('user_id', '=', $userId)
            ->where('limit_id', '=', $typeId)
            ->whereNotNull('implement_at')
            ->where('implement_at', '>', Carbon::now()->toDateTimeString())
            ->orderBy('created_at', 'desc')
            ->first();

        return $limit;
    }

    /**
     * @param $userId
     * @param $userSessionId
     * @param $typeId
     * @param $value
     * @return boolean
     */
    public static function SetLimitFor($userId, $userSessionId, $typeId, $value){
        $curr = self::GetCurrLimitFor($userId, $typeId);
        $last = self::GetLastLimitFor($userId, $typeId);
        if ($last != null){
            // check if its the same value
            if ($last->limit_value == $value)
                return true;
            else {
                // invalidate last change
                $last->implement_at = null;
                $last->update();
            }
        }
        $currVal = $curr != null? $curr->limit_value: null;
        if ($currVal == $value)
            return true;

        $limit = new UserLimit();
        $limit->user_id = $userId;
        $limit->user_session_id = $userSessionId;
        $limit->limit_id = $typeId;
        $limit->limit_value = $value;

        if ($currVal == null || ($value != null && $currVal > $value)){
            // change now
            $limit->implement_at = Carbon::now()->toDateTimeString();
        } else {
            $limit->implement_at = Carbon::now()->addHour(24)->toDateTimeString();
        }
        return $limit->save();
    }

    public static function GetValueOrNull($key, $array){
        return (array_key_exists($key, $array)? $array[$key]: null);
    }
}
