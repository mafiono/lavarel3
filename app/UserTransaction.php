<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTransaction extends Model
{
    protected $table = 'user_transactions';

  /**
    * Rules for deposits
    *
    * @var array
    */
    public static $rulesForDeposit = array(
        'payment_method' => 'required',
        'deposit_value' => 'required|numeric'
    );  

  /**
    * Messages for form validation
    *
    * @var array
    */
    public static $messages = array(
        'payment_method.required' => 'Preencha o método de pagamento',
        'deposit_value.required' => 'Preencha o valor a depositar',
        'deposit_value.numeric' => 'Apenas digitos são aceites'
    );     

  /**
    * Relation with User
    *
    */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function transaction()
    {
        return $this->hasOne('App\Transaction', 'id', 'transaction_id');
    }

  /**
    * Relation with Transaction
    *
    */
    public function setting()
    {
        return $this->belongsTo('App\Transaction', 'transaction_id', 'id');
    }    

  /**
    * Method to help building the validation error message array.
    *
    * @param $validator
    * @return array
    */
    public static function buildValidationMessageArray($validator, $edit = false) 
    {
        $messages = $validator->messages();

        $errors = [
            'payment_method' => $messages->first('payment_method'),
            'deposit_value' => $messages->first('deposit_value')
        ];

        return $errors;
    }               
  
  /**
    * Creates a new user transaction
    *
    * @param array data
    *
    * @return object UserStatus
    */
    public static function createTransaction($amount, $userId, $transactionId, $transactionType, $bankId = null, $userSessionId) 
    {                             
        $userTransaction = new UserTransaction;
        $userTransaction->user_id = $userId;
        $userTransaction->transaction_id = $transactionId;
        if ($transactionType == 'deposit')
            $userTransaction->credit = $amount;
        else {
            $userTransaction->charge = $amount;
            $userTransaction->processed = 0;
        }

        if (!empty($bankId))
            $userTransaction->bank_id = $bankId;

        $userTransaction->description = $transactionId .' '. $transactionType;
        $userTransaction->user_session_id = $userSessionId;
        $userTransaction->date = \Carbon\Carbon::now()->toDateTimeString();

        if (!$userTransaction->save())
            return false;

        return $userTransaction;
    }

}
