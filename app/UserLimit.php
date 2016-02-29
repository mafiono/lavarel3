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
    * @param array data
    * @param string $typeLimits 'Bets' or 'Deposits'
    *
    * @return boolean true or false
    */
    public static function changeLimits($data, $typeLimits)
    {
        switch (strtolower($typeLimits)){
            case 'deposits':
                self::SetLimitFor('limit_deposit_daily', self::GetValueOrNull('limit_daily', $data));
                self::SetLimitFor('limit_deposit_weekly', self::GetValueOrNull('limit_weekly', $data));
                self::SetLimitFor('limit_deposit_monthly', self::GetValueOrNull('limit_monthly', $data));
                break;
            case 'bets':
                self::SetLimitFor('limit_betting_daily', self::GetValueOrNull('limit_daily', $data));
                self::SetLimitFor('limit_betting_weekly', self::GetValueOrNull('limit_weekly', $data));
                self::SetLimitFor('limit_betting_monthly', self::GetValueOrNull('limit_monthly', $data));
                break;
        }
        return true;
    }

    /**
     * @param $typeId
     * @return UserLimit
     */
    public static function GetCurrLimitFor($typeId){
        $userId = User::getCurrentId();
        /* @var $limit UserLimit */
        $limit = self::where('user_id', '=', $userId)
            ->where('limit_id', '=', $typeId)
            ->whereNotNull('implement_at')
            ->where('implement_at', '<', Carbon::now()->toDateTimeString())
            ->orderBy('created_at', 'desc')
            ->first();

        return $limit;
    }

    /**
     * @param $typeId
     * @return UserLimit
     */
    public static function GetLastLimitFor($typeId){
        $userId = User::getCurrentId();
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
     * @param $typeId
     * @param $value
     * @return boolean
     */
    public static function SetLimitFor($typeId, $value){
        $curr = self::GetCurrLimitFor($typeId);
        $last = self::GetLastLimitFor($typeId);
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
        $limit->user_id = User::getCurrentId();
        $limit->user_session_id = UserSession::getSessionId();
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
