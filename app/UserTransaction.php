<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Validator;

/**
 * @property string transaction_id
 * @property string api_transaction_id
 * @property string date
 * @property int user_session_id
 * @property string description
 * @property int user_bank_account_id
 * @property string status_id
 * @property float $debit
 * @property float credit
 * @property string origin
 * @property int user_id
 */
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
        'deposit_value' => 'required|numeric|max:500|min:5'
    );  

  /**
    * Messages for form validation
    *
    * @var array
    */
    public static $messages = array(
        'payment_method.required' => 'Preencha o método de pagamento',
        'deposit_value.required' => 'Preencha o valor a depositar',
        'deposit_value.numeric' => 'Apenas digitos são aceites',
        'deposit_value.max' => 'O valor máximo de deposito paypal é 500€',
        'deposit_value.min' => 'O valor minimo de deposito paypal é 5€'
    );

    /**
     * Create a unique Hash for the transaction
     *
     * @param $userId User Id
     * @param $date Carbon Date
     * @return string Hash
     */
    public static function getHash($userId, $date)
    {
        $hash = $date->format('dmy').'U'.$userId.'T';
        $lastHash = UserTransaction::query()
            ->where('transaction_id', 'LIKE', $hash .'%')
            ->orderBy('transaction_id', 'desc')
            ->value('transaction_id');
        // TODO get last counter...
        if ($lastHash != null) {
            $lastNr = substr($lastHash, strlen($hash), 9);
            $lastNr++;
        } else {
            $lastNr = 1;
        }
        $hash .= sprintf('%09d', $lastNr);
        return $hash;
    }

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
     * @param $validator Validator
     * @param bool $edit
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
     * @param $amount
     * @param $userId
     * @param $transactionId
     * @param $transactionType
     * @param null $bankId
     * @param $userSessionId
     * @param $apiTransactionId
     * @return UserTransaction UserTransaction
     */
    public static function createTransaction($amount, $userId, $transactionId, $transactionType,
                                             $bankId = null, $userSessionId, $apiTransactionId = null)
    {
        $date = Carbon::now();
        $hash = UserTransaction::getHash($userId, $date);
        $userTransaction = new UserTransaction;
        $userTransaction->user_id = $userId;
        $userTransaction->transaction_id = $hash;
        $userTransaction->api_transaction_id = $apiTransactionId;
        $userTransaction->status_id = 'pending';
        $userTransaction->origin = $transactionId;

        $desc = 'Levantamento ';
        if ($transactionType == 'deposit'){
            $userTransaction->status_id = 'canceled';
            $userTransaction->debit = $amount;
            $desc = 'Depósito ';
        }
        else {
            $userTransaction->credit = $amount;
        }

        if (!empty($bankId))
            $userTransaction->user_bank_account_id = $bankId;

        $descTrans = Transaction::findOrNew($transactionId);

        $userTransaction->description = $desc . $descTrans->name .' '. $hash;
        $userTransaction->user_session_id = $userSessionId;
        $userTransaction->date = $date->toDateTimeString();

        if (!$userTransaction->save())
            return false;

        return $userTransaction;
    }

    /**
     * Update the status of a Transaction
     *
     * @param $userId
     * @param $transactionId
     * @param $amount
     * @param $statusId
     * @param $userSessionId
     * @param $apiTransactionId
     * @param $initial_balance
     * @param $final_balance
     * @return bool
     */
    public static function updateTransaction($userId, $transactionId, $amount, $statusId, $userSessionId, $apiTransactionId, $initial_balance, $final_balance){
        $trans = UserTransaction::query()
            ->where('user_id', '=', $userId)
            ->where('transaction_id', '=', $transactionId)
            ->first();

        if ($trans == null) {
            return false;
        }
        /* confirm value */
        if (($trans->debit + $trans->credit) != $amount){
            return false;
        }
        if ($apiTransactionId != null) {
            $trans->api_transaction_id = $apiTransactionId;
        }

        $trans->initial_balance = $initial_balance;
        $trans->final_balance = $final_balance;
        $trans->status_id = $statusId;
        $trans->user_session_id = $userSessionId;

        return $trans->save();
    }
}
