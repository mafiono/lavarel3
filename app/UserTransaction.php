<?php

namespace App;

use App\Events\WithdrawalWasRequested;
use App\Exceptions\DepositException;
use App\Traits\MainDatabase;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Validator;

/**
 * @property float debit
 * @property float cost
 * @property float credit
 * @property float tax
 * @property float debit_bonus
 * @property float credit_bonus
 * @property float debit_reserve
 * @property float credit_reserve
 * @property int user_bank_account_id
 * @property int user_id
 * @property int user_session_id
 * @property string api_transaction_id
 * @property string date
 * @property string description
 * @property string origin
 * @property string status_id
 * @property string transaction_id
 */
class UserTransaction extends Model
{
    use MainDatabase;
    protected $table = 'user_transactions';

    protected $fillable = [
        'user_id',
        'origin',
        'transaction_id',
        'api_transaction_id',
        'debit',
        'debit_bonus',
        'credit_bonus',
        'credit',
        'credit_bonus',
        'initial_balance',
        'initial_bonus',
        'final_balance',
        'final_bonus',
        'date',
        'description',
        'status_id',
    ];

  /**
    * Rules for deposits
    *
    * @var array
    */
    public static $rulesForDeposit = array(
        'payment_method' => 'required',
        'deposit_value' => 'required|numeric|min:10'
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
        'deposit_value.min' => 'O valor minimo de deposito é 10€'
    );

    /**
     * Create a unique Hash for the transaction
     *
     * @param $userId int User Id
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
     * @param $tax
     * @return UserTransaction UserTransaction
     */
    public static function createTransaction($amount, $userId, $transactionId, $transactionType,
                                             $bankId = null, $userSessionId, $apiTransactionId = null,
                                             $tax = null)
    {
        $date = Carbon::now();
        $hash = UserTransaction::getHash($userId, $date);
        $userTransaction = new UserTransaction;
        $userTransaction->user_id = $userId;
        $userTransaction->transaction_id = $hash;
        $userTransaction->api_transaction_id = $apiTransactionId;
        $userTransaction->status_id = 'pending';
        $userTransaction->origin = $transactionId;
        $userTransaction->tax = $tax ?? 0;

        $desc = 'Levantamento ';
        if ($transactionType === 'deposit'){
            $userTransaction->status_id = 'canceled';
            $userTransaction->debit = $amount;
            $desc = 'Depósito ';
        }
        else {
            if ($userTransaction->origin === 'pay_safe_card'
                && app()->bound('pay_safe_card.payout')) {
                $payout = app('pay_safe_card.payout');
                $userTransaction->api_transaction_id = $payout->id;
            }
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

        if ($transactionType === 'withdrawal') {
            event(new WithdrawalWasRequested($userTransaction));
        }

        return $userTransaction;
    }

    /**
     * Update the status of a Transaction
     *
     * @param $userId
     * @param $transactionId
     * @param $amount
     * @param $reserved
     * @param $statusId
     * @param $userSessionId
     * @param $apiTransactionId
     * @param $details
     * @param $balance
     * @param $cost
     * @param $force
     * @return bool
     * @throws DepositException
     */
    public static function updateTransaction($userId, $transactionId, $amount, $reserved, $statusId, $userSessionId,
                                             $apiTransactionId, $details, $balance, $cost = 0, $force = false){
        /** @var UserTransaction $trans */
        $query = UserTransaction::query()
            ->where('user_id', '=', $userId)
            ->where('transaction_id', '=', $transactionId);
        if ($apiTransactionId !== null) {
            $query->where('api_transaction_id', '=', $apiTransactionId);
        }
        $trans = $query->first();

        if ($trans === null) {
            throw new DepositException('transaction_not_found', 'Transaction not found!');
        }
        /* confirm value */
        if (!$force && abs(($trans->debit + $trans->credit + $trans->tax) - $amount) > 0.01) {
            throw new DepositException('invalid_amount', 'Invalid Amount ' . ($trans->debit + $trans->credit + $trans->tax)
                . ' != ' . $amount);
        }
        if ($apiTransactionId != null) {
            $trans->api_transaction_id = $apiTransactionId;
        }
        $trans->fill($balance);
        $trans->status_id = $statusId;
        $trans->user_session_id = $userSessionId;
        $trans->cost = abs($cost);
        if ($details !== null) {
            $trans->transaction_details = $details;
        }
        if ($reserved > $trans->debit) {
            throw new DepositException('invalid_reserved_amount',
                "UserId:  Invalid Reserved Amount $reserved Where only $trans->debit Available");
        }
        if ($reserved > 0) {
            $trans->debit -= $reserved;
            $trans->debit_reserve = $reserved;
        }
        if ($statusId === 'processed') {
            $trans->date = Carbon::now()->toDateTimeString();
        }

        return $trans->save();
    }

//    public static function depositBonus(UserBonus $bonus)
//    {
//        $trans = new static();
//        $trans->origin = 'bonus';
//        $trans->user_id = $bonus->user_id;
//        $trans->transaction_id = self::getHash($bonus->user_id, Carbon::now());
//        $trans->debit = $bonus->user->balance->balance_bonus;
//        $trans->date = Carbon::now();
//
//        $trans->description = $bonus->id.' - '.$bonus->bonus->title;
//        $trans->status_id = 'processed';
//        $trans->user_session_id => UserSession::getSessionId();
//
//
//    }

    /**
     * Find UserTransaction
     *
     * @param $transId
     * @return UserTransaction
     */
    public static function findByTransactionId($transId) {
        return UserTransaction::where('transaction_id', '=', $transId)->first();
    }

    public function scopeDepositsFromUser($query, $userId)
    {
        return $query->where('user_id', $userId)
            ->where('debit', '>', 0)
            ->where('status_id', 'processed');
    }

    public function scopeLatestDeposits($query, $origins = null)
    {
        if (is_null($origins)) {
            $origins = ['bank_transfer','cc','mb','meo_wallet','paypal','pay_safe_card'];
        }

        return $query->whereStatusId('processed')
            ->whereIn('origin', $origins)
            ->where('debit', '>', 0)
            ->latest('id');
    }

    public function scopeLatestUserDeposits($query, $userId, $origins = null)
    {
        return $query->latestDeposits($origins)
            ->whereUserId($userId);
    }

    public function scopeLatestUserDeposit($query, $userId, $origins = null)
    {
        return $query->latestUserDeposits($userId, $origins)
            ->take(1);
    }
}
