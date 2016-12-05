<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserBankAccount
 * @property int user_session_id
 * @property string status_id
 * @property string iban
 * @property int user_document_id
 * @property int bank_account
 * @property int user_id
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
        'iban' => 'required|iban|unique:user_bank_accounts,iban,NULL,id,user_id,',
    );

    /**
     * Messages for form validation
     *
     * @var array
     */
    public static $messagesForCreateAccount = array(
        'bank.required' => 'Preencha o seu banco',
        'iban.required' => 'Preencha o seu iban',
        'iban.iban' => 'Introduza um Iban válido começando por PT50',
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
     * @return object UserStatus
     */
    public function createBankAccount($data, $userId, $userSessionId, $docId)
    {                             
        $userAccount = new UserBankAccount;
        $userAccount->user_id = $userId;
        $userAccount->bank_account = $data['bank'];
        $userAccount->iban = $data['iban'];
        $userAccount->status_id = 'waiting_confirmation';
        $userAccount->user_session_id = $userSessionId;
        $userAccount->user_document_id = $docId;

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

    public function toHumanFormat()
    {
        $iban = mb_strtoupper(str_replace(' ', '', $this->iban));

        # Add spaces every four characters
        $human_iban = '';
        for ($i = 0; $i < strlen($iban); $i++) {
            $human_iban .= substr($iban, $i, 1);
            if (($i > 0) && (($i + 1) % 4 == 0)) {
                $human_iban .= ' ';
            }
        }
        return $human_iban;
    }
}
