<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int user_id
 * @property int user_session_id
 * @property float balance_total
 * @property float balance_bonus
 * @property float balance_available
 * @property float balance_accounting
 *
 *
 * @property User user
 */
class UserBalance extends Model
{
    protected $table = 'user_balances';

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
        $userBalance->balance_total = 0;
        $userBalance->balance_available = 0;
        $userBalance->balance_bonus = 0;
        $userBalance->balance_accounting = 0;
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
        if ($transactionType == 'deposit')
            $this->balance_available += $amount;
        else
            $this->balance_available -= $amount;

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

        return $this->save();
    }

  /**
    * Ands to an User Balance
    *
    * @param array data
    *
    * @return boolean true or false
    */
    public function addAvailableBalance($amount)
    {
        $this->balance_available += $amount;

        return $this->save();
    }

    public function total() {
        return ($this->balance_available + $this->balance_bonus);
    }

}
