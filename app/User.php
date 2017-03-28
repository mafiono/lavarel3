<?php

namespace App;

use App\Lib\Mail\SendMail;
use App\Models;
use App\Models\Message;
use App\Models\UserComplain;
use App\Models\UserInvite;
use App\Models\Staff;
use App\Traits\MainDatabase;
use Auth;
use Cache;
use Carbon\Carbon;
use Exception;
use App\UserBet;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Log;
use Mail, Hash, DB;
use Request;
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
 * @property string friend_code
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
 * @property Carbon last_login_at
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use MainDatabase;
    use Authenticatable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public function getEmailForPasswordReset()
    {
        return $this->profile->email;
    }
    protected $table = 'users';

    /**
     * Convert dates to Carbon
     */
    protected $dates = ['created_at', 'updated_at', 'last_login_at'];

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
        'fullname' => 'required|max:100',
        'firstname' => 'required',
        'name' => 'required',
        'birth_date' => 'required|date|before:-18 Years',
        'nationality' => 'required',
        'document_number' => [
            'required',
            'min:6',
            'max:13',
            'cc',
            'unique_cc'
        ],
        'tax_number' => 'required|nif|digits_between:9,9',
        'sitprofession' => 'required',
        'country' => 'required',
        'address' => 'required|max:150',
        'city' => 'required',
        'zip_code' => [
            'required',
            'regex:/^[0-9]{4}-[0-9]{3}$/',
        ],
        'email' => 'required|max:100|email|unique:user_profiles,email',
        'conf_email' => 'required|email|same:email',
        'phone' => [
            'required',
            'regex:/^\+[0-9]{2,3}\s*[0-9]{6,11}$/',
        ],
        'username' => [
            'required',
            'min:5',
            'max:45',
            'regex:/^(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?![_.])$/',
            'unique:users,username',
        ],
        'password' => [
            'required',
            'min:8',
            'max:20',
            'regex:/^(?=.*[A-Z])(?=.*[0-9])(?=.*[a-z]).{8,20}$/',
        ],
        'conf_password' => 'required|same:password',
        'security_pin' => 'required|min:4|max:4',
        'general_conditions' => 'required',
        'bank_name' => '',
        'bank_bic' => '',
        'bank_iban' => 'iban',
        'captcha' => 'required|captcha'
    );

  /**
    * Rules for general form validation
    *
    * @var array
    */
    public static $rulesForRegisterStep3 = array(
        'bank' => 'required',
        'iban' => 'required|iban',
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
        'security_pin' => 'required||min:4|max:4',
        'conf_security_pin' => 'required||min:4|max:4|same:security_pin'
    );  

  /**
    * Rules for change limits
    *
    * @var array
    */
    public static $rulesForLimits = array(
        'limit_daily_bet' => 'numeric',
        'limit_weekly_bet' => 'numeric',
        'limit_monthly_bet' => 'numeric',
        'limit_daily_deposit'=> 'numeric',
        'limit_weekly_deposit'=> 'numeric',
        'limit_monthly_deposit'=> 'numeric'

    );


    public static $messagesForLimits = array(
        'limit_daily_bet.numeric' => 'Apenas são aceites dígitos no formato x.xx',
        'limit_weekly_bet.numeric' => 'Apenas são aceites dígitos no formato x.xx',
        'limit_monthly_bet.numeric' => 'Apenas são aceites dígitos no formato x.xx',
        'limit_daily_deposit.numeric' => 'Apenas são aceites dígitos no formato x.xx',
        'limit_weekly_deposit.numeric' => 'Apenas são aceites dígitos no formato x.xx',
        'limit_monthly_deposit.numeric' => 'Apenas são aceites dígitos no formato x.xx',
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
        'phone' => [
            'required',
            'regex:/\+[0-9]{2,3}\s*[0-9]{6,11}/',
        ],
    );

  /**
    * Messages for form validation
    *
    * @var array
    */
    public static $messagesForRegister = array(
        'gender.required' => 'Por favor preencha o título',
        'fullname.max' => 'O nome completo não pode exceder 100 caracteres',
        'firstname.required' => 'Preencha o seu primeiro nome',
        'name.required' => 'Preencha o seu nome Apelido',
        'birth_date.required' => 'Preencha a sua data nascimento',
        'birth_date.date' => 'Formato de data inválido',
        'birth_date.before' => 'Precisa de ter mais de 18 anos para se registar',
        'document_number.required' => 'Preencha o seu nº de identificação',
        'document_number.numeric' => 'Apenas digitos são aceites',
        'document_number.min' => 'Este campo terá de ter pelo menos 6 caracteres',
        'document_number.max' => 'Este campo terá de ter no máximo 13 caracteres',
        'document_number.cc' => 'Indica nr de BI, CC ou Passaporte',
        'document_number.unique_cc' => 'Este nr já se encontra registado',
        'tax_number.required' => 'Preencha o seu NIF',
        'tax_number.nif' => 'Introduza um NIF válido',
        'tax_number.digits_between' => 'Este campo deve ter 9 digitos',
        'tax_number.unique' => 'Este NIF já se encontra registado',
        'sitprofession.required' => 'Preencha a sua situação profissional',
        'profession.required' => 'Preencha a sua profissão',
        'country.required' => 'Preencha o seu nome país',
        'address.required' => 'Preencha a sua morada',
        'city.required' => 'Preencha a sua cidade',
        'zip_code.required' => 'Preencha o seu código postal',
        'zip_code.regex' => 'Código postal deve ter o formato XXXX-XXX',
        'email.required' => 'Preencha o seu email',
        'email.email' => 'Insira um email válido',
        'email.unique' => 'Email já se encontra registado',
        'email.max' => 'Email deve ter mais de 100 caracteres',
        'conf_email.required' => 'Confirme o seu email',
        'conf_email.email' => 'Insira um email válido',
        'conf_email.same' => 'Tem que ser igual ao seu email',
        'phone.required' => 'Preencha o seu telefone',
        'phone.regex' => 'Indique o codigo do pais e o numero',
        'username.required' => 'Preencha o seu utilizador',
        'username.unique' => 'Nome de Utilizador Indisponivel',
        'username.regex' => 'Formato inválido',
        'old_password.required' => 'Preencha a sua password actual',
        'password.required' => 'Preencha a sua password',
        'password.min' => 'Minimo 8 caracteres',
        'password.max' => 'Máximo 20 caracteres',
        'password.regex' => '1 maiúscula, 1 minúscula e 1 numero',
        'conf_password.required' => 'Confirme a sua password',
        'conf_password.same' => 'Tem que ser igual à sua password',
        'old_security_pin.required' => 'Preencha código de segurança antigo',
        'security_pin.required' => 'Preencha o seu código de segurança',
        'security_pin.min' => 'Este campo deve ter 4 caracteres',
        'security_pin.max' => 'Este campo deve ter 4 caracteres',
        'conf_security_pin.required' => 'Confirme o seu código de segurança',
        'conf_security_pin.same' => 'Tem que ser igual ao seu código de segurança',
        'general_conditions.required' => 'Tem de aceitar os Termos e Condições e Regras',
        'bank.required' => 'Preencha o seu banco',
        'iban.required' => 'Preencha o seu iban',
        'iban.iban' => 'Introduza um Iban válido começando por PT50',
        'bank_iban:iban' => 'Introduza um Iban válido começando por PT50',
        'captcha.required' => 'Introduza o código do captcha',
        'captcha.captcha' => 'Introduza o código correcto',
    );

    /**
     * Compare if 2 dates are not equal
     * @param Carbon $a
     * @param Carbon $b
     * @return bool
     */
    private static function datesNotEquals($a, $b)
    {
        if ($a === null && $b === null)
            return false;
        if ($a !== null && $b === null)
            return true;
        if ($a === null && $b !== null)
            return true;
        if ($a->diffInHours($b) > 1)
            return true;
        return false;
    }

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

    public function activeBonus($origin = 'sport')
    {
        return $this->hasOne('\App\UserBonus', 'user_id', 'id')
            ->join('bonus', 'user_bonus.bonus_id', '=', 'bonus.id')
            ->select('user_bonus.*', 'bonus.bonus_origin_id')
            ->where('bonus_origin_id', $origin)
            ->where('active', 1);
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
        return $this->bankAccounts()
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
        return $this->confirmedBankAccounts()->where('id', '=', $id)->first() !== null;
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
    public static function buildValidationMessageArray($validator, $keys = [])
    {
        $messages = $validator->messages();

        $errors = [
            'gender' => $messages->first('gender'),
            'fullname' => $messages->first('fullname'),
            'firstname' => $messages->first('firstname'),
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
            'captcha' => $messages->first('captcha'),
        ];
        if (count($keys) > 0) {
            $errors = array_intersect_key($errors, $keys);
        }

        return $errors;
    }

    /**
     * Creates a new User, User Profile, User Status and User Settings
     *
     * @param $data
     * @param $callback
     * @return bool | User
     */
    public function signUp($data, $callback = null)
    {
        try {
            DB::beginTransaction();

            $userData = [
                'username' => $data['username'],
                'password' => Hash::make($data['password']),
                'security_pin' => $data['security_pin'],
                'identity_checked' => $data['identity_checked'],
                'identity_method' => $data['identity_method'],
                'identity_date' => Carbon::now()->toDateTimeString(),
                'promo_code' => $data['promo_code'],
                'currency' => 'euro',
                'user_role_id' => 'player',
                'api_password' => str_random(40)
            ];

            foreach ($userData as $key => $value)
                $this->$key = $value;

            $this->rating_status = 'pending';
            // TODO validate if the code exists on DB.
            $friendId = null;
            if (! empty($this->friend_code)) {
                $friend = self::query()->where('user_code', '=', $this->friend_code)->first(['id']);
                if ($friend === null)
                    throw new Exception('sign_up.invalid.friend_code');
                $friendId = $friend->id;
            }
            if (! $this->save()) {
                throw new Exception('sign_up.fail.save');
            }
            do {
                /* Create a unique hash */
                $this->user_code = strtoupper(self::makeHash($this->id));
                $uk = self::query()->where('user_code', '=', $this->user_code)->first(['id']);
            } while ($uk !== null);

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
            if (! $this->sendMailSignUp($data, $token, $userSession->id)){
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
            if ($friendId !== null) {
                if (! UserInvite::createInvite($friendId, $this->id, $this->friend_code, $data['email'])) {
                    throw new Exception('sign_up.friend.invite');
                }
            }

            /* Allow invoking a callback inside the transaction */
            if (is_callable($callback)) {
                if (!$callback($this, $userSession->id)) {
                    throw new Exception('sign_up.fail.callback');
                }
            }

            $this->sendBeginnerMessage();

            DB::commit();

            return $userSession;
        } catch (Exception $e){
            DB::rollBack();
            Session::forget('user_id');
            throw $e;
        }
    }

    /**
     * Confirm email for the user
     *
     * @param $email string
     * @param $token string
     *
     * @return bool true or false
     * @throws Exception
     */
    public function confirmEmail($email, $token)
    {
        try {
            DB::beginTransaction();
            /** @var UserProfile $profile */
            $profile = UserProfile::query()->where('email', '=', $email)->first();
            if ($profile === null) {
                throw new Exception('errors.profile_not_found');
            }

            $sessionUserId = Session::get('user_id', null);
            if ($sessionUserId !== null && $sessionUserId !== $profile->user_id) {
                throw new Exception('errors.not_same_user');
            }

            $this->id = $profile->user_id;
            Session::put('user_id', $this->id);

            if ($profile->email !== $email) {
                throw new Exception('errors.not_same_email');
            }

            if ($profile->email_checked !== 0) {
                throw new Exception('errors.email_already_checked');
            }

            if ($profile->email_token !== $token) {
                throw new Exception('errors.invalid_token');
            }

            /* Create User Session */
            if (!$userSession = $this->logUserSession('confirmed.email', 'email_confirmed')) {
                throw new Exception('errors.fail_on_save_log');
            }

            $profile->user_session_id = $userSession->id;
            $profile->email_checked = 1;
            if (!$profile->save()) {
                throw new Exception('errors.fail_on_save');
            }

            /* Create User Email Status */
            if (!$this->setStatus('confirmed', 'email_status_id')) {
                throw new Exception('errors.fail_change_status');
            }

            DB::commit();
            Session::forget('user_id');
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Session::forget('user_id');
            throw $e;
        }
    }

    /**
     * Creates a new user session
     *
     * @param $type
     * @param $description
     * @param bool $newSession
     *
     * @return UserSession or false
     */
    public function logUserSession($type, $description, $newSession = false)
    {
        $us = UserSession::logSession($type, $description, $this->id, $newSession);
        switch ($type) {
            case 'user_agent':
            case 'login_fail':
                // TODO check if this needs a try catch...
                $ua = urlencode($description);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "http://useragentapi.com/api/v3/json/9ac01ad4/$ua");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

                $result = curl_exec($ch);
                $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

    //          print_r($result);
                $ff = json_decode($result)->data;
                $text = "";
                foreach ($ff as $k => $v) {
                    $text .= $k . ': '. $v.'; ';
                }
                $us = UserSession::logSession('device', $text, $this->id, $newSession);
                break;
            case 'login':
                $this->last_login_at = Carbon::now()->toDateTimeString();
                $this->save();
                break;
        }
        return $us;
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
     * @param $data
     * @param UserDocument $doc
     * @param bool $useTrans
     * @return UserBankAccount|false
     */
    public function createBankAndIban($data, UserDocument $doc = null, $useTrans = true)
    {
        try {
            // TODO change this to use a try catch
            if ($useTrans)
                DB::beginTransaction();

            /* Create User Session */
            if (! $userSession = $this->logUserSession('create.iban', 'create_iban')) {
                throw new Exception('errors.creating_session');
            }
            /** @var UserBankAccount $bankAccount */
            $bankAccount = (new UserBankAccount)->createBankAccount($data, $this->id, $userSession->id, isset($doc) ? $doc->id:null);
            /* Create Bank Account  */
            if ($bankAccount === null) {
                throw new Exception('errors.creating_bank_account');
            }

            /* Create User Iban Status */
            if (! $this->setStatus('waiting_document', 'iban_status_id')) {
                throw new Exception('errors.fail_change_status');
            }

            if ($useTrans)
                DB::commit();
            return $bankAccount;
        } catch (Exception $e) {
            if ($useTrans)
                DB::rollBack();
            return false;
        }
    }

    /**
     * Create a User Bank Account for Paypal
     *
     * @param $data
     * @return UserBankAccount | false
     */
    public function createPayPalAccount($data)
    {
        try {
            // TODO change this to use a try catch
            DB::beginTransaction();

            /* Create User Session */
            if (! $userSession = $this->logUserSession('create.paypal', 'create_paypal')) {
                throw new Exception('errors.creating_session');
            }
            /** @var UserBankAccount $bankAccount */
            $bankAccount = (new UserBankAccount)->createPayPalAccount($data, $this->id, $userSession->id);
            /* Create Bank Account  */
            if (empty($bankAccount)) {
                throw new Exception('errors.creating_bank_account');
            }

            /* Create User Iban Status */
            if (! $this->setStatus('confirmed', 'iban_status_id')) {
                throw new Exception('errors.fail_change_status');
            }

            DB::commit();
            return $bankAccount;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }
  /**
    * Adds a new User Document
    *
    * @param array $file info
    * @param string $type document type
    *
    * @return UserDocument|false
    */
    public function addDocument($file, $type)
    {
        try {
            DB::beginTransaction();

            if (!$doc = UserDocument::saveDocument($this, $file, $type)) {
                throw new Exception('errors.saving_doc');
            }

            /* Create User Session */
            if (!$userSession = $this->logUserSession('uploaded_doc.' . $type, 'uploaded doc ' . $type)) {
                throw new Exception('errors.creating_session');
            }

            $statusTypeId = null;
            switch ($type) {
                case 'comprovativo_identidade':
                    $statusTypeId = 'identity_status_id';
                    break;
                case 'comprovativo_morada':
                    $statusTypeId = 'address_status_id';
                    break;
                case 'comprovativo_iban':
                    $statusTypeId = 'iban_status_id';
                    break;
                default:
                    break;
            }
            if ($statusTypeId !== null) {
                /* Create User Status */
                if (!$this->setStatus('waiting_confirmation', $statusTypeId)) {
                    throw new Exception('errors.fail_change_status');
                }
            }

            DB::commit();
            return $doc;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
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
            DB::rollBack();
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
            DB::rollBack();
            return false;
        }

        DB::commit();
        return $user;
    }

  /**
    * Updates User Profile
    *
    * @param array $data
    * @param boolean $addressChanged
    *
    * @return boolean true or false
    */
    public function updateProfile($data, $addressChanged)
    {
        try{
            /* Create User Session */
            if (! $userSession = $this->logUserSession('change_profile', 'change_profile')) {
                //TODO change this names
                throw new Exception('change_profile.log');
            }

            if (! $this->profile->updateProfile($data, $userSession->id)){
                throw new Exception('change_profile.update');
            }

            /* Create User Status */
            if ($addressChanged && ! $this->setStatus('waiting_document', 'address_status_id')) {
                throw new Exception('change_profile.status');
            }
            return true;

        }catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Created a new User Transaction (Deposit)
     *
     * @param $amount
     * @param $transactionId
     * @param $tax float fee
     * @param $apiTransactionId
     * @return UserTransaction|bool User transaction or False
     */
    public function newDeposit($amount, $transactionId, $tax, $apiTransactionId = null)
    {
        DB::beginTransaction();

        /* Create User Session */
        if (! $userSession = $this->logUserSession('deposit.'.$transactionId, 'deposit '. $transactionId . ': '. $amount)) {
            DB::rollBack();
            return false;
        }

        if (! $trans = UserTransaction::createTransaction($amount, $this->id, $transactionId,
            'deposit', null, $userSession->id, $apiTransactionId, $tax)){
            DB::rollBack();
            return false;
        };
//
//        // Update balance to captive
//        if (! $this->balance->addToCaptive($amount)){
//            DB::rollBack();
//            return false;
//        }

        DB::commit();
        return $trans;
    }
    public function checkCanSelfExclude(){
        $erros = 0;
        // $erros += in_array($this->status->status_id, ['active'])?0:1;
        $erros += $this->status->identity_status_id === 'confirmed'?0:1;

        return $erros === 0;
    }
    public function checkCanDeposit(){
        $erros = 0;
        // $erros += in_array($this->status->status_id, ['active'])?0:1;
        $erros += $this->status->identity_status_id === 'confirmed'?0:1;

        return $erros === 0;
    }
    public function checkCanWithdraw(){
        $erros = 0;
        $erros += in_array($this->status->status_id, ['approved', 'suspended', 'disabled'])?0:1;
        $erros += $this->status->identity_status_id === 'confirmed'?0:1;
        $erros += $this->status->email_status_id === 'confirmed'?0:1;
        $erros += $this->status->address_status_id === 'confirmed'?0:1;
        $erros += $this->status->iban_status_id === 'confirmed'?0:1;

        return $erros === 0;
    }

    public function whyCanWithdraw(){
        $erros = [];
        if (!in_array($this->status->status_id, ['approved', 'suspended', 'disabled'])) {
            $erros['status_id'] = $this->status->status_id;
        }
        $erros['identity_status_id'] = $this->status->identity_status_id;
        $erros['email_status_id'] = $this->status->email_status_id;
        $erros['address_status_id'] = $this->status->address_status_id;
        $erros['iban_status_id'] = $this->status->iban_status_id;
        return $erros;
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
     * @param $apiTransactionId
     * @return bool | UserTransaction
     */
    public function newWithdrawal($amount, $transactionId, $bankId, $apiTransactionId = null)
    {
        try {
            DB::beginTransaction();

            /* Create User Session */
            if (! $userSession = $this->logUserSession('withdrawal.'. $transactionId, 'withdrawal '. $transactionId . ': '. $amount)) {
                throw new Exception('errors.creating_session');
            }

            if (! $trans =  UserTransaction::createTransaction($amount, $this->id, $transactionId,
                'withdrawal', $bankId, $userSession->id, $apiTransactionId)){
                throw new Exception('errors.creating_transaction');
            };

            $trans->initial_balance = $this->balance->balance_available;
            // Update balance from Available to Accounting
            if (! $this->balance->moveToCaptive((int) $amount)){
                throw new Exception('errors.move_to_captive');
            }
            $trans->final_balance  = $this->balance->balance_available;

            if (! $trans->save()) {
                throw new Exception('errors.saving_transaction');
            }

            $mail = new SendMail(SendMail::$TYPE_5_WITHDRAW_REQUEST);
            $mail->prepareMail($this, [
                    'title' => 'Pedido de Levantamento',
                    'value' => number_format($amount, 2, ',', ' '),
                ], $userSession->id);
            $mail->Send(true);

            DB::commit();
            return $trans;
        } catch (Exception $e) {
            Log::error('Error on Withdraw'. $e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    /**
     * Update the status of a transaction
     *
     * @param $transactionId
     * @param $amount
     * @param $statusId
     * @param $userSessionId
     * @param $apiTransactionId
     * @param $details
     * @return UserTransaction
     */
    public function updateTransaction($transactionId, $amount, $statusId, $userSessionId, $apiTransactionId = null, $details = null)
    {

        $trans = UserTransaction::findByTransactionId($transactionId);
        if ($trans && $trans->status_id === 'processed')
            return false;

        DB::beginTransaction();

        /* Create User Session */
        if (! $userSession = $this->logUserSession('change_trans.'.$trans->origin,
            'change transaction '. $transactionId . ': '. $amount . ' To: ' . $statusId)) {
            DB::rollBack();
            return false;
        }
        $initial_balance = null;
        $final_balance = null;
        if ($statusId === 'processed') {
            // Update balance to Available
            $initial_balance = $this->balance->balance_available;
            if (! $this->balance->addAvailableBalance($trans->credit + $trans->debit)){
                DB::rollBack();
                return false;
            }
            $final_balance = $this->balance->balance_available;
        }

        if (! UserTransaction::updateTransaction($this->id, $transactionId,
            $amount, $statusId, $userSessionId, $apiTransactionId, $details, $initial_balance, $final_balance)){
            DB::rollBack();
            return false;
        }

        DB::commit();

        /* Send email to user */
        $mail = new SendMail(SendMail::$TYPE_4_NEW_DEPOSIT);
        $mail->prepareMail($this, [
            'title' => 'Depósito efetuado com sucesso',
            'value' => number_format($amount, 2, ',', ' '),
        ], $userSession->id);
        $mail->Send(false);

        return $trans;
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
            DB::rollBack();
            return false;
        }

        if (!$this->balance->subtractAvailableBalance($userBet->amount)) {
            DB::rollBack();
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
            DB::rollBack();
            return false;
        }

        if ($userBet->result === 'Returned' || $userBet->result === 'Won' || $userBet->result === 'BC Deposit' || $userBet->result === 'Bet Recalculated More') {
            if (!$this->balance->addAvailableBalance($amount)) {
                DB::rollBack();
                return false;
            }
        }elseif($userBet->result === 'Bet Recalculated Less') {
            if (!$this->balance->subtractAvailableBalance($amount)) {
                DB::rollBack();
                return false;
            }
        }else{
            DB::rollBack();
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
            DB::rollBack();
            return false;
        }

        if (! $userLimit = UserLimit::changeLimits($data, $typeLimits)){
            DB::rollBack();
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
    * @return bool | UserSelfExclusion
    */
    public function selfExclusionRequest($data)
    {
        try {
            if (empty($data['self_exclusion_type']))
                return false;

            DB::beginTransaction();

            $type = $data['self_exclusion_type'];

            /* Create User Session */
            if (! $userSession = $this->logUserSession('self_exclusion.'. $type, 'self-exclusion of '. $type)) {
                throw new Exception('errors.creating_session');
            }
            if (! $selfExclusion = UserSelfExclusion::selfExclusionRequest($data, $this->id)){
                throw new Exception('errors.creating_user_self_exclusion');
            }

            /* Create User Status */
            if (! $this->setStatus($type, 'selfexclusion_status_id')) {
                throw new Exception('errors.changing_status');
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
                    throw new Exception('errors.creating_list_self_exclusion');
                }
            }

            if ('undetermined_period' === $type){
                // TODO Transfer available to User
                if ($this->balance->balance_available > 0 && $this->checkCanWithdraw()) {
                    $bank = $this->bankAccountsInUse()->first();
                    if ($bank !== null) {
                        $this->newWithdrawal($this->balance->balance_available, 'bank_transfer', $bank->id);
                    }
                }
            } else {
                // TODO inactive the account

            }

            $mail = new SendMail(SendMail::$TYPE_12_SELF_EXCLUSION);
            $mail->prepareMail($this, [
                'title' => 'reflection_period' !== $type ? 'Autoexclusão' : 'Reflexão',
                'exclusion' => $type,
                'time' => $data['se_meses'] ?? $data['rp_dias'] ?? 3,
            ], $userSession->id);
            $mail->Send(true);

            DB::commit();

            return $selfExclusion;
        }catch (Exception $ex) {
            DB::rollBack();
            return false;
        }
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
            DB::rollBack();
            return false;
        }

        if (! $userRevocation = UserRevocation::requestRevoke($this->id, $selfExclusion, $userSessionId)){
            DB::rollBack();
            return false;
        }

        if ($selfExclusion->self_exclusion_type_id === 'reflection_period'){
            if (! $selfExclusion->revoke()){
                DB::rollBack();
                return false;
            }
            /* Create User Status */
            if (! $this->setStatus(null, 'selfexclusion_status_id')) {
                throw new Exception('errors.changing_status');
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
            DB::rollBack();
            return false;
        }

        if (! $revocation->cancelRevoke()){
            DB::rollBack();
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
            $msg = '';

            $selfExclusionSRIJ = ListSelfExclusion::validateSelfExclusion([
                'document_number'=>$this->profile->document_number
            ]);
            $selfExclusion = $this->getSelfExclusion();
            if ($selfExclusionSRIJ !== null) {
                // Add to self exclusion
                if ($selfExclusion !== null){
                    // Check if its the same
                    if (self::datesNotEquals($selfExclusion->request_date, $selfExclusionSRIJ->start_date)
                        || self::datesNotEquals($selfExclusion->end_date, $selfExclusionSRIJ->end_date)){
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
            if ($selfExclusion !== null){
                // Validate this exclusion
                $selfRevocation = $selfExclusion->hasRevocation();
                if ($selfRevocation !== null){
                    // we have a revocation
                    // lets check when selfExclusion stated to validate min of 3 months.
                    $daysSE = $selfExclusion->request_date->diffInDays();
                    $daysR = $selfRevocation->request_date->diffInDays();
                    // TODO validate this, When SRIJ === Null can be a connection error...
                    if ($selfExclusionSRIJ === null ||
                        ($daysSE > 90 && $daysR > 30)){
                        // we can process this Revocation
                        if (! $selfRevocation->processRevoke())
                            throw new Exception('Error processing Revocation!');
                        if (! $selfExclusion->process())
                            throw new Exception('Error processing Self Exclusion!');
                        if (! $this->setStatus(null, 'selfexclusion_status_id'))
                            throw new Exception('Error changing Status!');
                    } else {
                        // criar msg
                        $msg = $selfExclusion->self_exclusion_type_id.' Until: '.$selfExclusion->end_date;
                        $msg .= ' Revocation-On: '.$selfRevocation->request_date;
                    }
                } else if ($selfExclusionSRIJ === null){
                    // When SRIJ don't have exclusion revoke it from ours.
                    // if its a reflection period, don't revoke
                    if ($selfExclusion->self_exclusion_type_id !== 'reflection_period') {
                        if (!$selfExclusion->process())
                            throw new Exception('Error processing Self Exclusion!');
                        if (! $this->setStatus(null, 'selfexclusion_status_id'))
                            throw new Exception('Error changing Status!');
                    } else {
                        // criar msg
                        $msg = $selfExclusion->self_exclusion_type_id.' Until: '.$selfExclusion->end_date;
                    }
                } else {
                    // criar msg
                    $msg = $selfExclusion->self_exclusion_type_id.' Until: '.$selfExclusion->end_date;
                }
            } else {
                // All is good check status of the user.
            }
            $msg = 'Status: '.$this->status->status_id.' Self-Exclusion: '.$msg;

            DB::commit();
            return $msg;
        } catch (Exception $e){
            DB::rollBack();
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
        return $profile !== null ? $profile->user()->first() : null;
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
            DB::rollBack();
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

    public function sendMailSignUp($data, $token, $userSessionId)
    {
        try {
            $type = $data['identity_checked'] ? SendMail::$TYPE_1_SIGN_UP_SUCCESS : SendMail::$TYPE_2_SIGN_UP_IDENTITY;
            $mail = new SendMail($type);
            $mail->prepareMail($this, [
                'title' => 'BEM-VINDO AO CASINO PORTUGAL',
                'url' => Request::getUriForPath('/').'/confirmar_email?email='.$data['email'].'&token='.$token,
            ], $userSessionId);
            $mail->Send(true);
            return true;
        } catch (Exception $e) {
            Log::error("Error Sending Email. ". $e->getMessage());
            throw new Exception('sign_up.fail.send_email');
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
        return UserSession::where('user_id', $this->id)
            ->where('session_type', '!=', 'login_fail')
            ->where('session_type', '!=', 'device')
            ->orderBy('id', 'desc')->first();
    }

    public function isSelfExcluded() {
        return $this->status->selfexclusion_status_id !== null;
    }


    public function complaints()
    {
        return $this->hasMany(UserComplain::class);
    }

    public function lastSeenNow()
    {
        // use cache instead of go to Sql every time.
        $last_date = Cache::get('last_seen_' . $this->id, $this->getLastSession()->created_at);
        $last_date = new Carbon($last_date);

        if ($last_date < Carbon::now()->subMinute(config('session.lifetime'))) {
            // last action was 20 minutes in past
            // force a logout
            $this->logoutOldSessions();
            return true;
        } else {
            // Save session
            Cache::put('last_seen_' . $this->id, $last_date, 2);
        }

        return false;
    }

    public function logoutOldSessions()
    {
        $us = $this->getLastSession();
        if (!in_array($us->session_type, ['logout', 'timeout'], true)) {
            $us->exists = false;
            $us->id = null;
            $time = Carbon::parse($us->created_at)->addMinutes(config('session.lifetime'));
            if ($time->isPast()) {
                // timeOut
                $us->session_type = 'timeout';
                $us->description = 'Session Timeout';
            } else {
                // logOff
                $time = Carbon::now();
                $us->session_type = 'logout';
                $us->description = 'Session closed by new Login';
            }
            $us->updated_at = $us->created_at = $time;
            $us->save();
        }
        return $us;
    }

    private function sendBeginnerMessage()
    {
        $staff = Staff::query()->where('username', '=', 'admin')->first();
        if ($staff === null)
            throw new Exception('fail.save');

        $message = new Message();
        $message->user_id = $this->id;
        $message->staff_id = $staff->id;
        $message->sender_id = $staff->id;
        $message->text = "Bem-vindo ao Casino Portugal!\nA Equipa Casino Portugal deseja-lhe boa sorte!";
        $message->save();
    }
}
