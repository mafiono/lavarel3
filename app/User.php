<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Mail, Hash, DB;

/**
 * @property mixed id
 * @property UserBalance balance
 * @property UserProfile profile
 *
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

  /**
    * Rules for general form validation
    *
    * @var array
    */
    public static $rulesForRegisterStep1 = array(
        'gender' => 'required',
        'name' => 'required',
        'birth_date' => 'required|date|before:-18 Years',
        'nationality' => 'required',
        'document_number' => 'required',
        'tax_number' => 'required|numeric|digits_between:9,9|unique:user_profiles,tax_number',
        'profession' => 'required',
        'country' => 'required',
        'address' => 'required',
        'city' => 'required',
        'zip_code' => 'required',
        'email' => 'required|email|unique:user_profiles,email',
        'conf_email' => 'required|email|same:email',
        'phone' => 'required|numeric',
        'username' => 'required|unique:users,username',
        'password' => 'required|min:6',
        'conf_password' => 'required|min:6|same:password',
        'security_pin' => 'required|min:5',
        'general_conditions' => 'required'
    );  

  /**
    * Rules for general form validation
    *
    * @var array
    */
    public static $rulesForRegisterStep3 = array(
        'bank' => 'required',
        'iban' => 'required|numeric|digits:21',
    );  

  /**
    * Rules for change password
    *
    * @var array
    */
    public static $rulesForChangePassword = array(
        'old_password' => 'required',
        'password' => 'required|min:6',
        'conf_password' => 'required|min:6|same:password'
    );

  /**
    * Rules for change pin
    *
    * @var array
    */
    public static $rulesForChangePin = array(
        'old_security_pin' => 'required',
        'security_pin' => 'required|min:5',
        'conf_security_pin' => 'required|min:5|same:security_pin'
    );  

  /**
    * Rules for change limits
    *
    * @var array
    */
    public static $rulesForLimits = array(
        'limit_daily' => 'numeric',
        'limit_weekly' => 'numeric',
        'limit_monthly' => 'numeric'
    );


    public static $messagesForLimits = array(
        'limit_daily.numeric' => 'Apenas são aceites dígitos no formato x.xx',
        'limit_weekly.numeric' => 'Apenas são aceites dígitos no formato x.xx',
        'limit_monthly.numeric' => 'Apenas são aceites dígitos no formato x.xx',
    );

    /**
    * Rules for update profile
    *
    * @var array
    */
    public static $rulesForUpdateProfile = array(
        'profession' => 'required',
        'country' => 'required',
        'address' => 'required',
        'city' => 'required',
        'zip_code' => 'required',
        'phone' => 'required|numeric'
    );

  /**
    * Messages for form validation
    *
    * @var array
    */
    public static $messagesForRegister = array(
        'gender.required' => 'Por favor preencha o título',
        'name.required' => 'Preencha o seu nome completo',
        'birth_date.required' => 'Preencha a sua data nascimento',
        'birth_date.date' => 'Formato de data inválido',
        'birth_date.before' => 'Precisa de ter mais de 18 anos para se registar',
        'document_number.required' => 'Preencha o seu nº de identificação',
        'document_number.numeric' => 'Apenas digitos são aceites',
        'document_number.digits_between' => 'Este campo terá de ter 9 digitos',
        'document_number.unique' => 'Esta identificação já se encontra registada',
        'tax_number.required' => 'Preencha o seu NIF',
        'tax_number.numeric' => 'Apenas digitos são aceites',
        'tax_number.digits_between' => 'Este campo deve ter 9 digitos',
        'tax_number.unique' => 'Este NIF já se encontra registado',
        'profession.required' => 'Preencha a sua profissão',
        'country.required' => 'Preencha o seu nome país',
        'address.required' => 'Preencha a sua morada',
        'city.required' => 'Preencha a sua cidade',
        'zip_code.required' => 'Preencha o seu código postal',
        'email.required' => 'Preencha o seu email',
        'email.email' => 'Insira um email válido',
        'email.unique' => 'Email já se encontra registado',
        'conf_email.required' => 'Confirme o seu email',
        'conf_email.email' => 'Insira um email válido',
        'conf_email.same' => 'Tem que ser igual ao seu email',
        'phone.required' => 'Preencha o seu telefone',
        'phone.numeric' => 'Apenas digitos são aceites',
        'username.required' => 'Preencha o seu utilizador',
        'username.unique' => 'Nome de Utilizador Indisponivel',
        'old_password.required' => 'Preencha a sua password antiga',
        'password.required' => 'Preencha a sua password',
        'password.min' => 'Este campo deve ter mais de 5 digitos',        
        'conf_password.required' => 'Confirme a sua password',
        'conf_password.min' => 'Este campo deve ter mais de 5 caracteres',        
        'conf_password.same' => 'Tem que ser igual à sua password',
        'old_security_pin.required' => 'Preencha código de segurança antigo',
        'security_pin.required' => 'Preencha o seu código de segurança',
        'security_pin.min' => 'Este campo deve ter mais de 4 caracteres',        
        'conf_security_pin.required' => 'Confirme o seu código de segurança',
        'conf_security_pin.min' => 'Este campo deve ter mais de 4 caracteres',        
        'conf_security_pin.same' => 'Tem que ser igual ao seu código de segurança',
        'general_conditions.required' => 'Tem de aceitar os Termos e Condições e Regras',
        'bank.required' => 'Preencha o seu banco',
        'iban.required' => 'Preencha o seu iban',
        'iban.digits' => 'O Iban é composto por 23 caracteres, excluíndo os primeiros dois dígitos PT',
    );

  /**
    * Relation with User Profile
    *
    */
    public function profile()
    {
        return $this->hasOne('App\UserProfile', 'user_id', 'id');
    }
  /**
    * Relation with User Status (Current)
    *
    */
    public function status()
    {
        return $this->hasOne('App\UserStatus', 'user_id', 'id')->where('current', '=', 1);
    }

  /**
    * Relation with User Status (All)
    *
    */
    public function statuses()
    {
        return $this->hasMany('App\UserStatus', 'user_id', 'id');
    }
  /**
    * Relation with User Settings
    *
    */
    public function settings()
    {
        return $this->hasMany('App\UserSetting', 'user_id', 'id');
    }
  /**
    * Relation with User Self Exclusion
    *
    */
    public function selfExclusion()
    {
        return $this->hasMany('App\UserSelfExclusion', 'user_id', 'id');
    }
    /**
     * User Has and active Self Exclusion
     *
     * @return UserSelfExclusion
     */
    public function getSelfExclusion()
    {
        return UserSelfExclusion::getCurrent($this->id);
    }

  /**
    * Relation with User Documentation
    *
    */
    public function documents()
    {
        return $this->hasMany('App\UserDocument', 'user_id', 'id');
    }
  /**
    * Relation with User Session
    *
    */
    public function sessions()
    {
        return $this->hasMany('App\UserSession', 'user_id', 'id');
    }
  /**
    * Relation with User Balance
    *
    */
    public function balance()
    {
        return $this->hasOne('App\UserBalance', 'user_id', 'id');
    }
  /**
    * Relation with User Transaction
    *
    */
    public function transactions()
    {
        return $this->hasMany('App\UserTransaction', 'user_id', 'id');
    }
  /**
    * Relation with User Bank Account
    *
    */
    public function bankAccounts()
    {
        return $this->hasMany('App\UserBankAccount', 'user_id', 'id');
    }

    public function bankAccountsInUse()
    {
        return $this->hasMany('App\UserBankAccount', 'user_id', 'id')->where('status_id', 'in_use');
    }

    /**
     * Relation with User Bank Account
     *
     */
    public function confirmedBankAccounts()
    {
        return $this->hasMany('App\UserBankAccount', 'user_id', 'id')->where('status_id', 'confirmed');
    }
  /**
    * Relation with User Limit
    *
    */
    public function limits()
    {
        return $this->hasOne('App\UserLimit', 'user_id', 'id');
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
            'gender' => $messages->first('gender'),
            'name' => $messages->first('name'),
            'birth_date' => $messages->first('birth_date'),
            'nationality' => $messages->first('nationality'),
            'document_number' => $messages->first('document_number'),
            'tax_number' => $messages->first('tax_number'),
            'profession' => $messages->first('profession'),
            'country' => $messages->first('country'),
            'address' => $messages->first('address'),
            'city' => $messages->first('city'),
            'zip_code' => $messages->first('zip_code'),
            'email' => $messages->first('email'),
            'conf_email' => $messages->first('conf_email'),
            'phone' => $messages->first('phone'),
            'username' => $messages->first('username'),
            'password' => $messages->first('password'),
            'conf_password' => $messages->first('conf_password'),
            'security_pin' => $messages->first('security_pin'),
            'general_conditions' => $messages->first('general_conditions'),
            'limit_betting_daily' => $messages->first('limit_betting_daily'),
            'limit_betting_weekly' => $messages->first('limit_betting_weekly'),
            'limit_betting_monthly' => $messages->first('limit_betting_monthly'),
            'conf_security_pin' => $messages->first('conf_security_pin'),
        ];

        return $errors;
    }

    /**
     * Creates a new User, User Profile, User Status and User Settings
     *
     * @param $data
     * @param $callback
     * @return bool true or false
     */
    public function signUp($data, $callback = null)
    {
        DB::beginTransaction();

        $userData = [
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'security_pin' => $data['security_pin'],
            'identity_checked' => 1,
            'identity_date' => \Carbon\Carbon::now()->toDateTimeString(),
            'promo_code' => $data['promo_code'],
            'currency' => $data['currency'],
            'user_role_id' => 'player',
            'api_password' => str_random(40)
        ];

        foreach ($userData as $key => $value)
            $this->$key = $value;

        if (! $this->save()) {
            DB::rollback();
            return false;
        }

        /* Create User Session */
        if (! $userSession = $this->createUserSession(['description' => 'sign_up'])) {
            DB::rollback();
            return false;            
        }

        /* Create Token to send in Mail */
        if (! $token = $this->createConfirmMailToken()){
            DB::rollback();
            return false;
        }

        /* Create User Profile */
        if (! $this->createUserProfile($data, $userSession->id, $token)) {
            DB::rollback();
            return false;
        }

        /* Create User Status */
        if (! $this->setStatus('waiting_confirmation', 'status_id', $userSession->id)) {
            DB::rollback();
            return false;
        }

        /* Create User Initial Settings */
        if (! $this->createInitialSettings($userSession->id)) {
            DB::rollback();
            return false;
        }

        /* Create User Balance */
        if (! $this->createInitialBalance($userSession->id)) {
            DB::rollback();
            return false;
        }

        /* Create User Session */
        if (! $userSession = $this->createUserSession(['description' => 'check_identity'])) {
            DB::rollback();
            return false;
        }

        /* Send confirmation Email */
        if (! $this->sendMailSignUp($data, $token)){
            DB::rollback();
            return false;
        }

        /* Create User Email Status */
        if (! $this->setStatus('waiting_confirmation', 'email_status_id', $userSession->id)) {
            DB::rollback();
            return false;
        }

        /* Create User Session */
        if (! $userSession = $this->createUserSession(['description' => 'sent_confirm_mail'])) {
            DB::rollback();
            return false;
        }

        /* Allow invoking a callback inside the transaction */
        if (is_callable($callback)) {
            if (! $callback($this, $userSession->id)) {
                DB::rollback();
                return false;
            }
        }

        DB::commit();

        return $userSession;
    }

    /**
     * Confirm email for the user
     *
     * @param string email
     * @param string token
     *
     * @return boolean true or false
     */
    public function confirmEmail($email, $token)
    {
        DB::beginTransaction();

        $profile = UserProfile::query()->where('email', '=', $email)->first();
        if ($profile == null) {
            DB::rollback();
            return false;
        }

        $sessionUserId = \Session::get('user_id', null);
        if ($sessionUserId != null && $sessionUserId != $profile->user_id) {
            DB::rollback();
            return false;
        }

        $this->id = $profile->user_id;

        if ($profile->email != $email){
            DB::rollback();
            return false;
        }

        if ($profile->email_checked != 0){
            DB::rollback();
            return false;
        }

        if ($profile->email_token != $token){
            DB::rollback();
            return false;
        }

        $profile->email_checked = 1;
        if (! $profile->save()){
            DB::rollback();
            return false;
        }

        /* Create User Session */
        if (! $userSession = $this->createUserSession(['description' => 'email_confirmed'])) {
            DB::rollback();
            return false;
        }

        /* Create User Email Status */
        if (! $this->setStatus('confirmed', 'email_status_id', $userSession->id)) {
            DB::rollback();
            return false;
        }

        DB::commit();

        return true;
    }

    /**
     * Creates a new user session
     *
     * @param array $data data
     * @param bool $newSession
     *
     * @return mix Object UserSession or false
     */
    public function createUserSession($data = [], $newSession = false)
    {
        return UserSession::createSession($this->id, $data, $newSession);
    }

  /**
    * Creates a new user profile
    *
    * @param array data
    * @param int $userSessionId Current User Session
    *
    * @return mix Object UserProfile or false
    */
    public function createUserProfile($data, $userSessionId, $token)
    {
        return (new UserProfile)->createProfile($data, $this->id, $userSessionId, $token);
    }

    /**
     * Creates a new user status
     *
     * @param $status
     * @param $type
     * @param int $userSessionId Current User Session
     *
     * @return mix Object UserProfile or false
     */
    public function setStatus($status, $type, $userSessionId)
    {
        return (new UserStatus)->setStatus($status, $type, $this->id, $userSessionId);
    }
  /**
    * Creates user initial settings
    *
    * @param array data
    * @param int $userSessionId Current User Session
    *
    * @return mix Object UserSettings or false
    */
    public function createInitialSettings($userSessionId)
    {
        return (new UserSetting)->createInitialSettings($this->id, $userSessionId);
    }
  /**
    * Creates user initial balance
    *
    * @param array data
    * @param int $userSessionId Current User Session
    *
    * @return mix Object UserBalance or false
    */
    public function createInitialBalance($userSessionId)
    {
        return (new UserBalance)->createInitialBalance($this->id, $userSessionId);
    }
  /**
    * Updates banco and iban fields for register step3
    *
    * @param array data
    * @param int $userSessionId Current User Session
    *
    * @return boolean true or false
    */
    public function createBankAndIban($data, $userSessionId)
    {
        DB::beginTransaction();

        $banckAccount = (new UserBankAccount)->createBankAccount($data, $this->id, $userSessionId);
        /* Create Bank Account  */
        if (empty($banckAccount)) {
            DB::rollback();
            return false;
        }

        /* Create User Session */
        if (! $userSession = $this->createUserSession(['description' => 'create_iban'])) {
            DB::rollback();
            return false;
        }

        /* Create User Iban Status */
        if (! $this->setStatus('waiting_document', 'iban_status_id', $userSession->id)) {
            DB::rollback();
            return false;
        }

        DB::commit();
        return $banckAccount;
    }

  /**
    * Adds a new User Document
    *
    * @param array $file info
    * @param string $type document type
    * @param int $userSessionId Current User Session
    *
    * @return boolean true or false
    */
    public function addDocument($file, $type, $userSessionId)
    {
        DB::beginTransaction();

        $document = (new UserDocument)->saveDocument($this, $file, $type, $userSessionId);

        /* Create User Session */
        if (! $userSession = $this->createUserSession(['description' => 'uploaded doc ' . $type])) {
            DB::rollback();
            return false;
        }

        $statusId = null;
        switch ($type) {
            case 'comprovativo_identidade': $statusId = 'identity_status_id'; break;
            case 'comprovativo_morada': $statusId = 'address_status_id'; break;
            case 'comprovativo_iban': $statusId = 'iban_status_id'; break;
            default: break;
        }
        if ($statusId != null) {
            /* Create User Status */
            if (! $this->setStatus('waiting_confirmation', $statusId, $userSession->id)) {
                DB::rollback();
                return false;
            }
        }

        DB::commit();
        return $document;
    }
  /**
    * Updates an user password
    *
    * @param string $password
    *
    * @return boolean true or false
    */
    public function newPassword($password)
    {
        DB::beginTransaction();

        $this->password = Hash::make($password);
        $user = $this->save();

        /* Create User Session */
        if (! $userSession = $this->createUserSession(['description' => 'change_password'])) {
            DB::rollback();
            return false;
        }

        DB::commit();
        return $user;
    }

    /**
     * Reset an user password
     *
     * @param string $password
     *
     * @return boolean true or false
     */
    public function resetPassword($password)
    {
        DB::beginTransaction();

        $this->password = Hash::make($password);
        $user = $this->save();

        /* Create User Session */
        if (! $userSession = $this->createUserSession(['description' => 'reset_password'])) {
            DB::rollback();
            return false;
        }

        DB::commit();
        return $user;
    }

  /**
    * Updates User Profile
    *
    * @param array data
    * @param int $userSessionId Current User Session
    *
    * @return boolean true or false
    */
    public function updateProfile($data, $userSessionId) 
    {
        return $this->profile->updateProfile($data, $userSessionId);
    }

    /**
     * Created a new User Transaction (Deposit)
     *
     * @param $amount
     * @param $transactionId
     * @param int $userSessionId Current User Session
     * @param $apiTransactionId
     * @return UserTransaction|bool User transaction or False
     */
    public function newDeposit($amount, $transactionId, $userSessionId, $apiTransactionId = null)
    {
        DB::beginTransaction();

        /* Create User Session */
        if (! $userSession = $this->createUserSession(['description' => 'deposit '. $transactionId . ': '. $amount])) {
            DB::rollback();
            return false;
        }

        if (! $trans = UserTransaction::createTransaction($amount, $this->id, $transactionId,
            'deposit', null, $userSessionId, $apiTransactionId)){
            DB::rollback();
            return false;
        };

        // Update balance is done on the update

        DB::commit();
        return $trans;
    }

    /**
     * Creates a new User Transaction (Withdrawal)
     *
     * @param $amount
     * @param $transactionId
     * @param $bankId
     * @param int $userSessionId Current User Session
     * @param $apiTransactionId
     * @return bool true or false
     */
    public function newWithdrawal($amount, $transactionId, $bankId, $userSessionId, $apiTransactionId = null)
    {
        DB::beginTransaction();

        /* Create User Session */
        if (! $userSession = $this->createUserSession(['description' => 'withdrawal '. $transactionId . ': '. $amount])) {
            DB::rollback();
            return false;
        }

        if (! $trans =  UserTransaction::createTransaction($amount, $this->id, $transactionId,
            'withdrawal', $bankId, $userSessionId, $apiTransactionId)){
            DB::rollback();
            return false;
        };

        // TODO create Update balance


        DB::commit();
        return $trans;

    }

    /**
     * Update the status of a transaction
     *
     * @param $transactionId
     * @param $amount
     * @param $statusId
     * @param $userSessionId
     * @param $apiTransactionId
     * @return UserTransaction
     */
    public function updateTransaction($transactionId, $amount, $statusId, $userSessionId, $apiTransactionId = null)
    {
        DB::beginTransaction();

        /* Create User Session */
        if (! $userSession = $this->createUserSession(['description' =>
            'change transaction '. $transactionId . ': '. $amount . ' To: ' . $statusId])) {
            DB::rollback();
            return false;
        }

        $trans = UserTransaction::updateTransaction($this->id, $transactionId, $amount, $statusId, $userSessionId, $apiTransactionId);

        // TODO update Balance

        DB::commit();
        return $trans;
    }

    /**
    * Updates User Balance
    *
    * @param array data
    * @param int $userSessionId Current User Session
    *
    * @return boolean true or false
    */
    public function updateBalance($amount, $userSessionId) 
    {
        /* @var $balance UserBalance */
        $balance = $this->balance;
        return $balance->updateBalance($amount, 'deposit', $userSessionId);
    }

  /**
    * Created a new Bet for the user
    *
    * @param array data
    * @param int $userSessionId Current User Session
    *
    * @return boolean true or false
    */
    public function newBet($bet) 
    {
        DB::beginTransaction();

        $userBet = new UserBet;
        foreach ($bet as $key => $value) {
            $userBet->{$key} = $value;
        }

        if (!$userBet->save() || !(new UserBetStatus)->setStatus('waiting_result', $userBet->id, $bet['user_session_id'] )) {
            DB::rollback();
            return false;
        }

        if (!$this->balance->subtractAvailableBalance($userBet->amount)) {
            DB::rollback();
            return false;
        }

//        if (!UserBetTransactions::createTransaction([
//            "user_bet_id" => $userBet->id,
//            "api_transaction_id" => $userBet->api_transaction_id,
//            "operation" => "withdrawal",
//            "amount" => $userBet->amount,
//            "description" => "bet",
//            "datetime" => \Carbon\Carbon::now(),
//        ])) {
//            DB::rollback();
//            return false;
//        }

        DB::commit();

        return $this;
    }
    public function updateBet($userBet, $amount, $status = "processed")
    {
        DB::beginTransaction();

        $userBet->status = $status;

        if (!$userBet->save() || !(new UserBetStatus)->setStatus('processed', $userBet->id, $userBet->user_session_id)) {
            DB::rollback();
            return false;
        }

        if ($userBet->result == 'Returned' || $userBet->result == 'Won' || $userBet->result == 'BC Deposit' || $userBet->result == 'Bet Recalculated More') {
            if (!$this->balance->addAvailableBalance($amount)) {
                DB::rollback();
                return false;
            }
        }elseif($userBet->result == 'Bet Recalculated Less') {
            if (!$this->balance->subtractAvailableBalance($amount)) {
                DB::rollback();
                return false;
            }
        }else{
            DB::rollback();
            return false;            
        }

        DB::commit();

        return $this;
    }

    public function checkIfTransactionExists($transactionId)
    {
        $userBet = UserBet::where('api_transaction_id', '=', $transactionId)->first();
        if (!$userBet) {
            return false;
        }

        return true;
    }

    public function getUserBetByBetId($betId)
    {
        return UserBet::where('api_bet_id', '=', $betId)->first();
    }

  /**
    * Updates User Settings
    *
    * @param array data
    * @param int $userSessionId Current User Session
    *
    * @return boolean true or false
    */
    public function updateSettings($data, $userSessionId) 
    {
        return UserSetting::updateSettings($data, $userSessionId);
    }

    /**
     * Change user limites
     *
     * @param array $data
     * @param string $typeLimits 'Bets' or 'Deposits'
     * @param $userSessionId
     *
     * @return bool
     */
    public function changeLimits($data, $typeLimits, $userSessionId)
    {
        DB::beginTransaction();

        /* Create User Session */
        if (! $userSession = $this->createUserSession(['description' => 'changed limits '. $typeLimits])) {
            DB::rollback();
            return false;
        }

        if (! $userLimit = UserLimit::changeLimits($data, $typeLimits, $this->id, $userSessionId)){
            DB::rollback();
            return false;
        }

        DB::commit();

        return $userLimit;
    }
  /**
    * User Self Exclusion Request
    *
    * @param array $data
    *
    * @return bool
    */
    public function selfExclusionRequest($data, $userSessionId)
    {
        if (empty($data['self_exclusion_type']))
            return false;

        DB::beginTransaction();

        $type = $data['self_exclusion_type'];

        /* Create User Session */
        if (! $userSession = $this->createUserSession(['description' => 'self-exclusion of '. $type])) {
            DB::rollback();
            return false;
        }
        /* @var $selfExclusion UserSelfExclusion */
        if (! $selfExclusion = UserSelfExclusion::selfExclusionRequest($data, $this->id, $userSessionId)){
            DB::rollback();
            return false;
        }

        /* Create User Status */
        if (! $this->setStatus($type, 'selfexclusion_status_id', $userSessionId)) {
            DB::rollback();
            return false;
        }
        $statusId = 'reflection_period' === $type ? 'reflection' : 'selfexclusion';
        if (! $this->setStatus($statusId, 'status_id', $userSessionId)) {
            DB::rollback();
            return false;
        }

        if ($statusId === 'selfexclusion'){
            $profile = $this->profile()->first();
            $listAdd = ListSelfExclusion::addSelfExclusion([
                'document_number' => $profile->document_number,
                'document_type_id' => $profile->document_type_id,
                'start_date' => $selfExclusion->request_date,
                'end_date' => $selfExclusion->end_date
            ]);
            if (! $listAdd){
                DB::rollback();
                return false;
            }
        }

        if ('undetermined_period' === $type){
            // TODO cancel account

        } else {
            // TODO inactive the account

        }

        DB::commit();

        return $selfExclusion;
    }
  /**
    * Returns an user give an username
    *
    * @param string $username
    *
    * @return User User
    */
    public static function findByUsername($username)
    {
        return self::where('username', '=', $username)->first();
    }

    /**
     * Find user by ID
     *
     * @param $id
     * @return User User
     */
    public static function findById($id)
    {
        return self::where('id', '=', $id)->first();
    }

    /**
    * Change user pin
    *
    * @param string $pin
    *
    * @return bool
    */
    public function changePin($pin)
    {
        DB::beginTransaction();

        $this->security_pin = $pin;

        $user = $this->save();

        /* Create User Session */
        if (! $userSession = $this->createUserSession(['description' => 'change_pin'])) {
            DB::rollback();
            return false;
        }

        DB::commit();
        return $user;
    }
    public function atualizaSaldoDeposito($amount) {
        
        $this->saldo_disponivel += $amount;
        $this->saldo_contabilistico += $amount;
        return $this->save();
    }

    public function atualizaSaldoLevantamento($amount) {
        
        $this->saldo_disponivel -= $amount;
        return $this->save();
    }

  /**
    * Formats player id to 8 digits
    *
    * @param array data
    *
    * @return boolean true or false
    */
    public function internalId() 
    {
        $id = $this->id;
        while (strlen($id) < 8)
            $id = '0'.$id;

        return $id;
    }
  /**
    * Returns an user give its api password
    *
    * @param string $apiPassword
    *
    * @return object User
    */
    public static function findByApiPassword($apiPassword)
    {
        return self::where('api_password', '=', $apiPassword)->first();
    }

    public function sendMailSignUp($data, $token)
    {
        try {
            $url = \Request::getHost().'/confirmar_email?'.$data['email'].'&token='.$token;
            $isTesting = env('APP_ENV', 'local');
            if ($isTesting == 'testing') {
                print_r("User: Mail {$data['email']} Token: {$token}, URL: {$url}");
            } else {
                Mail::send('portal.sign_up.emails.signup', ['data' => $data, 'token' => $token, 'url' => $url],
                    function ($m) use ($data) {
                        $m->to($data['email'], $data['name'])->subject('iBetUp - Registo de Utilizador!');
                    });
            }
            return true;
        } catch (\Exception $e) {
            //do nothing..
            return false;
        }
    }

    private function createConfirmMailToken()
    {
        return str_random(10);
    }

    public function getLastSession() {
        return UserSession::where('user_id', $this->id)->orderBy('id', 'desc')->first();
//        return $this->sessions()->orderBy('id', 'desc')->first();
    }
}
