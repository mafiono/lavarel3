<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBankAccount extends Model
{
    protected $table = 'user_bank_accounts';

    protected $fillable = ['status_id'];
    
  /**
    * Relation with User
    *
    */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /**
    * Creates a new user bank account
    *
    * @param array data
    *
    * @return object UserStatus
    */
    public function createBankAccount($data, $userId, $userSessionId) 
    {                             
        $userAccount = new UserBankAccount;
        $userAccount->user_id = $userId;
        $userAccount->bank_account = $data['bank'];
        $userAccount->iban = 'PT50'.$data['iban'];
        $userAccount->status_id = 'waiting_confirmation';
        $userAccount->user_session_id = $userSessionId;

        if (!$userAccount->save())
        	return false;

        return $userAccount;
    }

    /**
     * Relation with Status
     *
     */
    public function status()
    {
        return $this->hasOne('App\Status', 'id', 'status_id');
    }

}
