<?php

namespace App;

use App\Exceptions\CustomException;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserBankAccount
 * @property int user_id
 * @property string transfer_type_id
 * @property string bank_account
 * @property string bank_bic
 * @property string identity
 * @property string account_details
 * @property string status_id
 * @property boolean account_ready
 * @property boolean active
 * @property int user_document_id
 * @property int user_session_id
 * @package App
 *
 *
 */
class UserBankAccount extends Model
{
    protected $table = 'user_bank_accounts';

    protected $fillable = ['status_id'];

    /**
     * Rules for general form validation
     *
     * @var array
     */
    public static $rulesForCreateAccount = array(
        'bank' => 'required',
        'bic' => 'required|min:3',
        'iban' => 'required|iban',
    );

    /**
     * Messages for form validation
     *
     * @var array
     */
    public static $messagesForCreateAccount = array(
        'bank.required' => 'Preencha o seu banco',
        'iban.required' => 'Preencha o seu iban',
        'bic.required' => 'Preencha o campo',
        'bic.min' => 'Minimo 3 chars',
        'iban.iban' => 'Introduza um Iban válido',
        'iban.unique' => 'O Iban introduzido já existe no sitema.',
    );
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
     * @param $data
     * @param $userId
     * @param $userSessionId
     * @param $docId
     * @return UserBankAccount | false
     * @throws \App\Exceptions\CustomException
     */
    public function createBankAccount($data, $userId, $userSessionId, $docId)
    {
        /** @var UserBankAccount $userAccount */
        $userAccount = UserBankAccount::query()
            ->where('user_id', '=', $userId)
            ->where('identity', '=', $data['iban'])
            ->where('active', '=', '1')
            ->first() ?? new UserBankAccount();
        if ($userAccount->exists && !$userAccount->canDelete()) {
            throw new CustomException('bank.exists', 'Esta conta de pagamentos já está confirmada!');
        }
        $userAccount->user_id = $userId;
        $userAccount->transfer_type_id = 'bank_transfer';
        $userAccount->bank_account = $data['bank'];
        $userAccount->bank_bic = $data['bic'];
        $userAccount->identity = $data['iban'];
        $userAccount->status_id = 'waiting_confirmation';
        $userAccount->user_session_id = $userSessionId;
        $userAccount->user_document_id = $docId;

        if (!$userAccount->save())
        	return false;

        return $userAccount;
    }

    /**
     * Creates a new user bank account for paypal
     *
     * @param $data
     * @param $userId
     * @param $userSessionId
     * @return UserBankAccount | false
     */
    public function createPayPalAccount($data, $userId, $userSessionId)
    {
        $userAccount = new UserBankAccount;
        $userAccount->user_id = $userId;
        $userAccount->transfer_type_id = 'paypal';
        $userAccount->bank_account = $data['email'];
        $userAccount->identity = $data['payer_id'];
        $userAccount->status_id = 'confirmed';
        $userAccount->user_session_id = $userSessionId;
        $userAccount->user_document_id = null;

        if (!$userAccount->save())
            return false;

        return $userAccount;
    }

    /**
     * Creates a new user bank account for Paysafecard
     *
     * @param $data
     * @param $userId
     * @param $userSessionId
     * @return UserBankAccount | false
     */
    public function createMyPaySafeCardAccount($data, $userId, $userSessionId)
    {
        $userAccount = new UserBankAccount;
        $userAccount->user_id = $userId;
        $userAccount->transfer_type_id = 'pay_safe_card';
        $userAccount->bank_account = implode(' ', [$data->first_name ?? '', $data->last_name ?? '']);
        $userAccount->identity = $data->psc_id;
        $userAccount->account_details = json_encode($data);
        $userAccount->account_ready = false;
        $userAccount->status_id = 'confirmed';
        $userAccount->user_session_id = $userSessionId;
        $userAccount->user_document_id = null;

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

    public function toName()
    {
        switch ($this->transfer_type_id) {
            case 'paypal':
                return 'Paypal';
            case 'meo_wallet':
                return 'Meo Wallet';
            case 'bank_transfer':
                return $this->bank_account;
            case 'pay_safe_card':
                return 'My PaysafeCard';
            default: return 'Tipo desconhecido!';
        }
    }

    public function toBic()
    {
        switch ($this->transfer_type_id) {
            case 'bank_transfer':
                return $this->bank_bic;
            case 'paypal':
            case 'meo_wallet':
            case 'pay_safe_card':
            default: return '';
        }
    }

    public function toHumanFormat()
    {
        switch ($this->transfer_type_id){
            case 'paypal':
            case 'pay_safe_card':
            case 'meo_wallet': return $this->bank_account;
            case 'bank_transfer':
                $iban = mb_strtoupper(str_replace(' ', '', $this->identity));

                # Add spaces every four characters
                $human_iban = '';
                for ($i = 0, $iMax = strlen($iban); $i < $iMax; $i++) {
                    $human_iban .= $iban[$i];
                    if (($i > 0) && (($i + 1) % 4 === 0)) {
                        $human_iban .= ' ';
                    }
                }
                return $human_iban;
            default: return 'Tipo desconhecido!';
        }
    }

    public function canDelete() {
        return $this->status_id !== 'confirmed'
            && $this->status_id !== 'in_use';
    }
}
