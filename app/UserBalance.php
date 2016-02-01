<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
    * Creates a new Setting with all values true
    *
    * @param array data
    *
    * @return boolean true or false
    */
    public static function createInitialBalance($userId, $userSessionId) 
    {
        $userBalance = new UserBalance;
        $userBalance->user_id = $userId;
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
    * @param array data
    *
    * @return boolean true or false
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
}
