<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Session;

/**
 * @property int user_id
 * @property int user_session_id
 * @property float balance_available
 * @property float balance_captive
 * @property float balance_accounting
 * @property float balance_bonus
 * @property float balance_total
 *
 * @property float
 * @property float b_av_check
 * @property float b_ca_check
 * @property float b_ac_check
 * @property float b_bo_check
 * @property float b_to_check
 *
 * @property User user
 */
class UserBalance extends Model
{
    protected $table = 'user_balances';
    protected $primaryKey = 'user_id';

    public static function getBalance()
    {
        $userId = Auth::id() ?: Session::get('user_id');
        if ($userId == null)
            throw new \Exception("User not logged!");

        /* @var $balance UserBalance */
        $balance = self::find($userId);

        return $balance->balance_accounting;
    }

    /**
    * Relation with User
    *
    */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /**
     * Creates a new Balance
     *
     * @param $userId
     * @param $userSessionId
     * @return bool|UserBalance false or UserBalance
     */
    public static function createInitialBalance($userId, $userSessionId) 
    {
        $userBalance = new UserBalance;
        $userBalance->user_id = $userId;
        $userBalance->balance_available = 0;
        $userBalance->balance_captive = 0;
        $userBalance->balance_accounting = 0;
        $userBalance->balance_bonus = 0;
        $userBalance->balance_total = 0;
        $userBalance->user_session_id = $userSessionId;

        if (! $userBalance->save())
            return false;

        return $userBalance;
    }

    /**
     * Updates an User Balance
     *
     * @param $amount
     * @param $transactionType
     * @param $userSessionId
     *
     * @return bool true or false     *
     */
    public function updateBalance($amount, $transactionType, $userSessionId) 
    {
        if ($transactionType == 'deposit') {
            $this->balance_available += $amount;
            $this->balance_accounting += $amount;
            $this->balance_total += $amount;
        }
        else {
            $this->balance_available -= $amount;
            $this->balance_captive -= $amount;
            $this->balance_accounting -= $amount;
            $this->balance_total -= $amount;
        }
        $this->user_session_id = $userSessionId;

        if (!$this->save())
            return false;

        return true;
    }
  /**
    * Subtracts an User Balance
    *
    * @param array data
    *
    * @return boolean true or false
    */
    public function subtractAvailableBalance($amount)
    {
        $this->balance_available -= $amount;
        $this->balance_accounting -= $amount;
        $this->balance_total -= $amount;

        return $this->save();
    }

  /**
    * Adds to User Balance
    *
    * @param array data
    *
    * @return boolean true or false
    */
    public function addAvailableBalance($amount)
    {
        $this->balance_available += $amount;
        $this->balance_accounting += $amount;
        $this->balance_total += $amount;

        return $this->save();
    }

    /**
     * Adds balance to Accounting
     * @param $amount
     * @return bool
     */
    public function addToCaptive($amount)
    {
        $this->balance_captive += $amount;
        $this->balance_accounting += $amount;
        $this->balance_total += $amount;

        return $this->save();
    }

    /**
     * Subtracts balance to Accounting
     * @param $amount
     * @return bool
     */
    public function subtractToCaptive($amount)
    {
        $this->balance_captive -= $amount;
        $this->balance_accounting -= $amount;
        $this->balance_total -= $amount;

        return $this->save();
    }

    /**
     * Move Captive balance to Available
     * @param $amount
     * @return bool
     */
    public function moveToAvailable($amount)
    {
        $this->balance_captive -= $amount;
        $this->balance_available += $amount;

        return $this->save();
    }

    /**
     * Move Available balance to Captive
     * @param $amount
     * @return bool
     */
    public function moveToCaptive($amount)
    {
        $this->balance_available -= $amount;
        $this->balance_captive += $amount;

        return $this->save();
    }

    /**
     * Get the total balance
     * @return integer
     */
    public function total() {
        return $this->balance_available + $this->balance_bonus;
    }

}
