<?php

namespace App;

use App\Models;
use App\Models\UserInvite;
use Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Mail, Hash, DB;
use Session;

/**
 * User Class
 *
 * @property UserBalance balance
 * @property UserStatus status
 * @property UserLimit limits
 * @property UserProfile profile
 *
 * @property int id
 * @property string username
 * @property string password
 * @property string security_pin
 * @property boolean identity_checked
 * @property string identity_method
 * @property Carbon identity_date
 * @property string user_code
 * @property string promo_code
 * @property string currency
 * @property string user_role_id
 * @property string api_token
 * @property string api_password
 * @property string remember_token
 * @property string rating_risk
 * @property string rating_group
 * @property string rating_type
 * @property string rating_category
 * @property string rating_class
 * @property string rating_status
 * @property float ggr_sb
 * @property float ggr_casino
 * @property float margin_sb
 * @property float margin_casino
 * @property int staff_id
 * @property int staff_session_id
 * @property Carbon created_at
 * @property Carbon updated_at
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
        'document_number' => 'required|min:6|max:15',
        'tax_number' => 'required|numeric|digits_between:9,9|unique:user_profiles,tax_number',
        'sitprofession' => 'required',
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
        'security_pin' => 'required|min:4|max:4',
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
        'document_number.min' => 'Este campo terá de ter pelo menos 6 caracteres',
        'document_number.max' => 'Este campo terá de ter no máximo 15 caracteres',
        'document_number.unique' => 'Esta identificação já se encontra registada',
        'tax_number.required' => 'Preencha o seu NIF',
        'tax_number.numeric' => 'Apenas digitos são aceites',
        'tax_number.digits_between' => 'Este campo deve ter 9 digitos',
        'tax_number.unique' => 'Este NIF já se encontra registado',
        'sitprofession.required' => 'Preencha a sua situação profissional',
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
        'security_pin.min' => 'Este campo deve ter 4 caracteres',
        'security_pin.max' => 'Este campo deve ter 4 caracteres',
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
    public function settings() {
        return $this->hasOne('App\UserSetting');
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
     * Relation with User Invites (All)
     *
     */
    public function friendInvites()
    {
        return $this->hasMany('App\Models\UserInvite', 'user_id', 'id');
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
        return $this->hasMany('App\UserBankAccount', 'user_id', 'id')
            ->where(function($query){
                $query->where('status_id', '=', 'confirmed')
                    ->orWhere('status_id', '=', 'in_use');
            });
    }

    /**
     * @param $id
     * @return bool
     */
    public function isBankAccountConfirmed($id){
        return $this->confirmedBankAccounts()->where('id', '=', $id)->first() != null;
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
            'sitprofession' => $messages->first('sitprofession'),
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
        try {
            DB::beginTransaction();

            $userData = [
                'username' => $data['username'],
                'password' => Hash::make($data['password']),
                'security_pin' => $data['security_pin'],
                'identity_checked' => 1,
                'identity_date' => Carbon::now()->toDateTimeString(),
                'promo_code' => $data['promo_code'],
                'currency' => $data['currency'],
                'user_role_id' => 'player',
                'api_password' => str_random(40)
            ];

            foreach ($userData as $key => $value)
                $this->$key = $value;

            $this->rating_status = 'pending';
            // TODO validate if the code exists on DB.
            $friendId = null;
            if (! empty($this->promo_code)) {
                $friend = self::query()->where('user_code', '=', $this->promo_code)->first(['id']);
                if ($friend == null)
                    throw new Exception('sign_up.invalid.promo_code');
                $friendId = $friend->id;
            }
            if (! $this->save()) {
                throw new Exception('sign_up.fail.save');
            }
            do {
                /* Create a unique hash */
                $this->user_code = strtoupper(self::makeHash($this->id));
                $uk = self::query()->where('user_code', '=', $this->user_code)->first(['id']);
            } while ($uk != null);

            if (! $this->save()) {
                throw new Exception('sign_up.fail_update.user_code');
            }

            Session::put('user_id', $this->id);

            /* Create User Session */
            if (! $userSession = $this->logUserSession('sign_up', 'sign_up and t&c')) {
                throw new Exception('sign_up.fail.log_session');
            }

            /* Create Token to send in Mail */
            if (! $token = $this->createConfirmMailToken()){
                throw new Exception('sign_up.fail.create_token');
            }

            /* Create User Profile */
            if (! $this->createUserProfile($data, $userSession->id, $token)) {
                throw new Exception('sign_up.fail.create_profile');
            }

            /* Create User Initial Settings */
            if (! $this->createInitialSettings($userSession->id)) {
                throw new Exception('sign_up.fail.create_settings');
            }

            /* Create User Balance */
            if (! $this->createInitialBalance($userSession->id)) {
                throw new Exception('sign_up.fail.create_balance');
            }

            /* Create User Session */
            if (! $userSession = $this->logUserSession('check.identity', 'check_identity')) {
                throw new Exception('sign_up.fail.log_session');
            }

            /* Send confirmation Email */
            if (! $this->sendMailSignUp($data, $token)){
                throw new Exception('sign_up.fail.send_email');
            }

            /* Create User Email Status */
            if (! $this->setStatus('waiting_confirmation', 'email_status_id')) {
                throw new Exception('sign_up.change.email_status');
            }

            /* Create User Session */
            if (! $userSession = $this->logUserSession('sent.confirm_mail', 'sent_confirm_mail')) {
                throw new Exception('sign_up.log.email_status');
            }

            /* Create UserInvites for friend */
            if ($friendId != null) {
                if (! UserInvite::createInvite($friendId, $this->id, $this->promo_code, $data['email'])) {
                    throw new Exception('sign_up.friend.invite');
                }
            }

            /* Allow invoking a callback inside the transaction */
            if (is_callable($callback)) {
                if (!$callback($this, $userSession->id)) {
                    throw new Exception('sign_up.fail.callback');
                }
            }

            DB::commit();

            return $userSession;
        } catch (Exception $e){
            DB::rollback();
            Session::forget('user_id');
            throw $e;
        }
        return false;
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
        try {
            DB::beginTransaction();

            $profile = UserProfile::query()->where('email', '=', $email)->first();
            if ($profile == null) {
                DB::rollback();
                return false;
            }

            $sessionUserId = Session::get('user_id', null);
            if ($sessionUserId != null && $sessionUserId != $profile->user_id) {
                DB::rollback();
                return false;
            }

            $this->id = $profile->user_id;
            Session::put('user_id', $this->id);

            if ($profile->email != $email) {
                DB::rollback();
                return false;
            }

            if ($profile->email_checked != 0) {
                DB::rollback();
                return false;
            }

            if ($profile->email_token != $token) {
                DB::rollback();
                return false;
            }

            $profile->email_checked = 1;
            if (!$profile->save()) {
                DB::rollback();
                return false;
            }

            /* Create User Session */
            if (!$userSession = $this->logUserSession('confirmed.email', 'email_confirmed')) {
                DB::rollback();
                return false;
            }

            /* Create User Email Status */
            if (!$this->setStatus('confirmed', 'email_status_id')) {
                DB::rollback();
                return false;
            }

            DB::commit();
            Session::forget('user_id');
            return true;
        } catch (Exception $e) {
            DB::rollback();
            Session::forget('user_id');
            return false;
        }
    }

    /**
     * Creates a new user session
     *
     * @param $type
     * @param $description
     * @param bool $newSession
     *
     * @return mix Object UserSession or false
     */
    public function logUserSession($type, $description, $newSession = false)
    {
        return UserSession::logSession($type, $description, $this->id, $newSession);
    }

    /**
     * Creates a new user session
     *
     * @param array $data data
     * @param bool $newSession
     *
     * @return mix Object UserSession or false
     * @deprecated Use logUserSession instead
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
     *
     * @return mix Object UserProfile or false
     */
    public function setStatus($status, $type)
    {
        return UserStatus::setStatus($status, $type);
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
        if (! $userSession = $this->logUserSession('create.iban', 'create_iban')) {
            DB::rollback();
            return false;
        }

        /* Create User Iban Status */
        if (! $this->setStatus('waiting_document', 'iban_status_id')) {
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

        $document = (new UserDocument)->saveDocument($this, $file, $type);

        /* Create User Session */
        if (! $userSession = $this->logUserSession('uploaded_doc.'.$type, 'uploaded doc ' . $type)) {
            DB::rollback();
            return false;
        }

        $statusTypeId = null;
        switch ($type) {
            case 'comprovativo_identidade': $statusTypeId = 'identity_status_id'; break;
            case 'comprovativo_morada': $statusTypeId = 'address_status_id'; break;
            case 'comprovativo_iban': $statusTypeId = 'iban_status_id'; break;
            default: break;
        }
        if ($statusTypeId != null) {
            /* Create User Status */
            if (! $this->setStatus('waiting_confirmation', $statusTypeId)) {
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
        if (! $userSession = $this->logUserSession('change_password', 'change_password')) {
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
        if (! $userSession = $this->logUserSession('reset_password', 'reset_password')) {
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
    *
    * @return boolean true or false
    */
    public function updateProfile($data)
    {
        try{
            /* Create User Session */
            if (! $userSession = $this->logUserSession('reset_password', 'reset_password')) {
                DB::rollback();
                return false;
            }

            return $this->profile->updateProfile($data, $userSession->id);
        }catch (Exception $e) {
            return false;
        }
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
        if (! $userSession = $this->logUserSession('deposit.'.$transactionId, 'deposit '. $transactionId . ': '. $amount)) {
            DB::rollback();
            return false;
        }

        if (! $trans = UserTransaction::createTransaction($amount, $this->id, $transactionId,
            'deposit', null, $userSessionId, $apiTransactionId)){
            DB::rollback();
            return false;
        };
//
//        // Update balance to captive
//        if (! $this->balance->addToCaptive($amount)){
//            DB::rollback();
//            return false;
//        }

        DB::commit();
        return $trans;
    }
    public function checkCanSelfExclude(){
        $erros = 0;
        // $erros += in_array($this->status->status_id, ['active'])?0:1;
        $erros += $this->status->identity_status_id == 'confirmed'?0:1;

        return $erros == 0;
    }
    public function checkCanDeposit(){
        $erros = 0;
        // $erros += in_array($this->status->status_id, ['active'])?0:1;
        $erros += $this->status->identity_status_id == 'confirmed'?0:1;

        return $erros == 0;
    }
    public function checkCanWithdraw(){
        $erros = 0;
        $erros += in_array($this->status->status_id, ['active', 'suspended', 'disabled'])?0:1;
        $erros += $this->status->identity_status_id == 'confirmed'?0:1;
        $erros += $this->status->email_status_id == 'confirmed'?0:1;
        $erros += $this->status->address_status_id == 'confirmed'?0:1;
        $erros += $this->status->iban_status_id == 'confirmed'?0:1;

        return $erros == 0;
    }

    public function checkInDepositLimit($amount){
        $msg = [];

        if (!is_null($val = UserLimit::GetCurrLimitValue('limit_deposit_daily'))){
            $date = Carbon::now()->toDateString();
            $diario = $this->transactions()->where('status_id', '=', 'processed')
                ->where('date', '>', $date);
            $total = $diario->sum('debit');
            if ($total + $amount > $val)
                $msg['daily_value'] = "Já atingiu o limite diario.";
        }
        if (!is_null($val = UserLimit::GetCurrLimitValue('limit_deposit_weekly'))){
            $date = Carbon::parse('last sunday')->toDateString();
            $diario = $this->transactions()->where('status_id', '=', 'processed')
                ->where('date', '>', $date);
            $total = $diario->sum('debit');
            if ($total + $amount > $val)
                $msg['daily_value'] = "Já atingiu o limite semanal.";
        }
        if (!is_null($val = UserLimit::GetCurrLimitValue('limit_deposit_monthly'))){
            $date = Carbon::now()->day(1)->toDateString();
            $diario = $this->transactions()->where('status_id', '=', 'processed')
                ->where('date', '>', $date);
            $total = $diario->sum('debit');
            if ($total + $amount > $val)
                $msg['daily_value'] = "Já atingiu o limite mensal.";
        }
        return $msg;
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
        if (! $userSession = $this->logUserSession('withdrawal.'. $transactionId, 'withdrawal '. $transactionId . ': '. $amount)) {
            DB::rollback();
            return false;
        }

        if (! $trans =  UserTransaction::createTransaction($amount, $this->id, $transactionId,
            'withdrawal', $bankId, $userSessionId, $apiTransactionId)){
            DB::rollback();
            return false;
        };

        $this->balance = $this->balance->getTotal();
        // Update balance from Available to Accounting
        if (! $this->balance->moveToCaptive($amount)){
            $this->balance = $this->balance->getTotal();
            DB::rollback();
            return false;
        }

        if (! $this->balance->save()) {
            DB::rollBack();
            return false;
        }


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

        $trans = UserTransaction::findByTransactionId($transactionId);
        if ($trans && $trans->status_id == 'processed')
            return false;

        DB::beginTransaction();

        /* Create User Session */
        if (! $userSession = $this->logUserSession('change_trans.'.$trans->origin,
            'change transaction '. $transactionId . ': '. $amount . ' To: ' . $statusId)) {
            DB::rollback();
            return false;
        }

        if ($statusId === 'processed') {
            // Update balance to Available
            $initial_balance = $this->balance->getTotal();
            if (! $this->balance->addAvailableBalance($amount)){
                DB::rollback();
                return false;
            }
            $final_balance = $this->balance->getTotal();
        }

        if (! UserTransaction::updateTransaction($this->id, $transactionId,
            $amount, $statusId, $userSessionId, $apiTransactionId, $initial_balance, $final_balance)){
            DB::rollback();
            return false;
        }

        try {
            $user = Auth::user();
            $activeBonus = UserBonus::findActiveBonusByOrigin(Auth::user(), 'sport');

            if ($activeBonus) {
                if ($activeBonus->isFirstDepositBonusAllowed($user, $trans))
                    $activeBonus->applyFirstDepositBonus($user, $trans);
                if ($activeBonus->isDepositsBonusAllowed($trans))
                    $activeBonus->applyDepositsBonus($user, $trans);
            }
        } catch (Exception $e) {
            DB::rollback();
            return false;
        }

        DB::commit();
        return !!$trans;
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
     *
     * @return bool
     */
    public function changeLimits($data, $typeLimits)
    {
        DB::beginTransaction();

        /* Create User Session */
        if (! $userSession = $this->logUserSession('change_limits.'. $typeLimits, 'changed limits '. $typeLimits)) {
            DB::rollback();
            return false;
        }

        if (! $userLimit = UserLimit::changeLimits($data, $typeLimits)){
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
    public function selfExclusionRequest($data)
    {
        if (empty($data['self_exclusion_type']))
            return false;

        DB::beginTransaction();

        $type = $data['self_exclusion_type'];

        /* Create User Session */
        if (! $userSession = $this->logUserSession('self_exclusion.'. $type, 'self-exclusion of '. $type)) {
            DB::rollback();
            return false;
        }
        /* @var $selfExclusion UserSelfExclusion */
        if (! $selfExclusion = UserSelfExclusion::selfExclusionRequest($data, $this->id)){
            DB::rollback();
            return false;
        }

        /* Create User Status */
        if (! $this->setStatus($type, 'selfexclusion_status_id')) {
            DB::rollback();
            return false;
        }
        if (! $this->setStatus('inactive', 'status_id')) {
            DB::rollback();
            return false;
        }

        if ('reflection_period' !== $type){
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
     * Create a new User Revocation to a SelfExclusion Request
     *
     * @param UserSelfExclusion $selfExclusion
     * @param $userSessionId
     * @return bool true or false
     */
    public function requestRevoke(UserSelfExclusion $selfExclusion, $userSessionId){

        DB::beginTransaction();

        /* Create User Session */
        if (! $userSession = $this->logUserSession('self_exclusion.revocation', 'revocation of self-exclusion '. $selfExclusion->id)) {
            DB::rollback();
            return false;
        }

        if (! $userRevocation = UserRevocation::requestRevoke($this->id, $selfExclusion, $userSessionId)){
            DB::rollback();
            return false;
        }

        if ($selfExclusion->self_exclusion_type_id === 'reflection_period'){
            if (! $selfExclusion->revoke()){
                DB::rollback();
                return false;
            }
        }
        DB::commit();

        return $userRevocation;
    }

    /**
     * Cancel the User Revocation to a SelfExclusion Request
     *
     * @param UserRevocation $revocation
     * @param $userSessionId
     * @return bool true or false
     */
    public function cancelRevoke(UserRevocation $revocation, $userSessionId){

        DB::beginTransaction();

        /* Create User Session */
        if (! $userSession = $this->logUserSession('self_exclusion.cancel_revocation', 'cancel revocation of self-exclusion '. $revocation->id)) {
            DB::rollback();
            return false;
        }

        if (! $revocation->cancelRevoke()){
            DB::rollback();
            return false;
        }

        DB::commit();

        return $revocation;
    }

    /**
     * Check Self Exclusion Status of user
     *
     * @return bool
     */
    public function checkSelfExclusionStatus(){
        try{
            DB::beginTransaction();

            $selfExclusionSRIJ = ListSelfExclusion::validateSelfExclusion([
                'document_number'=>$this->profile->document_number
            ]);
            $selfExclusion = $this->getSelfExclusion();
            if ($selfExclusionSRIJ != null) {
                // Add to self exclusion
                if ($selfExclusion != null){
                    // Check if its the same
                    if ($selfExclusion->request_date->diffInHours($selfExclusionSRIJ->start_date) > 1
                        || ($selfExclusion->end_date != $selfExclusionSRIJ->end_date) // TODO rethink this logic
                        || ($selfExclusion->end_date == null && $selfExclusionSRIJ->end_date != null)
                        || ($selfExclusion->end_date != null && $selfExclusionSRIJ->end_date == null)
                        || $selfExclusion->end_date->diffInHours($selfExclusionSRIJ->end_date) > 1){
                        // Update if its not
                        if (! $userSession = $this->logUserSession('self_exclusion.from_srij', 'self-exclusion from SRIJ'))
                            throw new Exception('Error creating Session!');
                        if (! $selfExclusion = $selfExclusion->updateWithSRIJ($selfExclusionSRIJ))
                            throw new Exception('Error updating with SRIJ!');
                        if (! $this->setStatus($selfExclusion->self_exclusion_type_id, 'selfexclusion_status_id'))
                            throw new Exception('Error Changing Status!');
                    }
                } else {
                    // Create it
                    /* Create User Session */
                    if (! $userSession = $this->logUserSession('self_exclusion.from_srij', 'self-exclusion from SRIJ'))
                        throw new Exception('Error creating Session!');
                    if (! $selfExclusion = UserSelfExclusion::createFromSRIJ($selfExclusionSRIJ))
                        throw new Exception('Error creating with SRIJ!');
                    if (! $this->setStatus($selfExclusion->self_exclusion_type_id, 'selfexclusion_status_id'))
                        throw new Exception('Error Changing Status!');
                }
            }
            if ($selfExclusion != null){
                // Validate this exclusion
                $selfRevocation = $selfExclusion->hasRevocation();
                if ($selfRevocation != null){
                    // we have a revocation
                    // lets check when selfExclusion stated to validate min of 3 months.
                    $daysSE = $selfExclusion->request_date->diffInDays();
                    $daysR = $selfRevocation->request_date->diffInDays();
                    if ($selfExclusionSRIJ == null ||
                        ($daysSE > 90 && $daysR > 30)){
                        // we can process this Revocation
                        if (! $selfRevocation->processRevoke())
                            throw new Exception('Error processing Revocation!');
                        if (! $selfExclusion->process())
                            throw new Exception('Error processing Self Exclusion!');
                    }
                } else if ($selfExclusionSRIJ == null){
                    // When SRIJ don't have exclusion revoke it from ours.
                    // if its a reflection period, don't revoke
                    if ($selfExclusion->self_exclusion_type_id != 'reflection_period') {
                        if (!$selfExclusion->process())
                            throw new Exception('Error processing Self Exclusion!');
                    }
                }
            } else {
                // All is good check status of the user.
            }

            DB::commit();
            return true;
        } catch (Exception $e){
            DB::rollback();
            return false;
        }
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
     * Find user by ID
     *
     * @param $id
     * @return User User
     */
    public static function findByEmail($email)
    {
        $profile = UserProfile::findByEmail($email);
        return $profile != null ? $profile->user()->first() : false;
    }

    public function findDocsByType($type)
    {
        return $this->documents()
            ->where('type', '=', $type)
            ->get();
    }

    public function findDocById($id)
    {
        return $this->documents()
            ->where('id', '=', $id)
            ->first();
    }


    /**
     * Get the current User ID
     *
     * @return int User Id
     */
    public static function getCurrentId(){
        return Auth::id() ?: Session::get('user_id');
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
        if (! $userSession = $this->logUserSession('change_pin', 'change_pin')) {
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
            $url = \Request::getHost().'/confirmar_email?email='.$data['email'].'&token='.$token;
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
        } catch (Exception $e) {
            //do nothing..
            return false;
        }
    }

    private function createConfirmMailToken()
    {
        return str_random(10);
    }

    /**
     * Make a Unique 5 length hash from an ID
     * @param $id
     * @return string
     */
    private static function makeHash($id) {
        $n = $id + 104729;
        $hash = '';

        while ($n) {
            $c = $n % 26;
            $n = floor($n / 26);
            $hash .= chr(ord('a')+$c);
        }

        return str_pad($hash, 5, 'a', STR_PAD_LEFT);
    }

    /**
     * Get last user session
     * @return UserSession
     */
    public function getLastSession() {
        return UserSession::where('user_id', $this->id)->orderBy('id', 'desc')->first();
    }


    public function isSelfExcluded() {
        $data['document_number'] = $this->profile->document_number;
        return ListSelfExclusion::validateSelfExclusion($data)
            || $this->authUser->getSelfExclusion();
    }
}
