<?php
namespace App\Http\Controllers;

use App\Enums\DocumentTypes;
use App\Http\Traits\GenericResponseTrait;
use App\Lib\Captcha\SimpleCaptcha;
use App\Lib\IdentityVerifier\PedidoVerificacaoTPType;
use App\Lib\IdentityVerifier\VerificacaoIdentidade;
use App\Lib\Mail\SendMail;
use App\Models\Country;
use App\Models\UserMail;
use App\PasswordReset;
use App\Providers\RulesValidator;
use App\UserSession;
use Auth, View, Validator, Response, Session, Mail;
use Cache;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\User, App\ListSelfExclusion, App\ListIdentityCheck;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;
use App\Lib\BetConstructApi;
use Log;
use JWTAuth;

class AuthController extends Controller
{
    use GenericResponseTrait;

    protected $request;
    protected $authUser;
    protected $betConstruct;
    /**
     * Constructor
     */
    public function __construct(Request $request, BetConstructApi $betConstruct)
    {
        $this->request = $request;
        $this->authUser = Auth::user();
        $this->betConstruct = $betConstruct;
        View::share('authUser', $this->authUser, 'request', $request);
    }

    public function captcha() {
        $refresh = $this->request->get('refresh', false);
        $captcha = new SimpleCaptcha('/captcha');
        if ($refresh) {
            $codes = $captcha->generateCaptcha();
            Session::put('captcha', $codes['session']);
        }
        $captcha->drawImg(Session::get('captcha'));
        return null;
    }
    /**
     * Step 1 of user's registration process
     *
     * @return Response|String
     */
    public function registarStep1()
    {
        if (Auth::check()) {
            // redirect back users from regist page.
            return "<script>page('/');</script>";
        }

        $captcha = (new SimpleCaptcha('/captcha'))->generateCaptcha();
        Session::put('captcha', $captcha['session']);

        $countryList = Country::query()
            ->where('cod_num', '>', 0)
            ->orderby('name')->lists('name','cod_alf2')->all();
        $natList = Country::query()
            ->where('cod_num', '>', 0)->whereNotNull('nationality')
            ->orderby('nationality')->lists('nationality','cod_alf2')->all();
        $sitProfList = [
            '' => '',
            '11' => 'Trabalhador por conta própria',
            '22' => 'Trabalhador por conta de outrem',
            '33' => 'Profissional liberal',
            '44' => 'Estudante',
            '55' => 'Reformado',
            '66' => 'Estagiário',
            '77' => 'Sem atividade profissional',
            '88' => 'Desempregado',
        ];
        $inputs = '';
        if(Session::has('inputs'))
            $inputs = Session::get('inputs');
        return View::make('portal.sign_up.step_1', compact('inputs', 'countryList', 'natList', 'sitProfList', 'captcha'));
    }
    /**
     * Handle POST for Step1
     *
     * @return JsonResponse
     */
    public function registarStep1Post()
    {
        $inputs = $this->request->all();
        $inputs['birth_date'] = $inputs['age_year'].'-'.sprintf("%02d", $inputs['age_month']).'-'.sprintf("%02d",$inputs['age_day']);
        $sitProf = $inputs['sitprofession'];
        $sitProfList = [
            '' => '',
            '11' => 'Trabalhador por conta própria',
            '22' => 'Trabalhador por conta de outrem',
            '33' => 'Profissional liberal',
            '44' => 'Estudante',
            '55' => 'Reformado',
            '66' => 'Estagiário',
            '77' => 'Sem atividade profissional',
            '88' => 'Desempregado',
            '99' => 'Outro',
        ];
        $inputs['profession'] = $sitProfList[$sitProf];
        $inputs['fullname'] = $inputs['firstname'].' '.$inputs['name'];

        $validator = Validator::make($inputs, User::$rulesForRegisterStep1, User::$messagesForRegister);
        if ($validator->fails()) {
            $messages = User::buildValidationMessageArray($validator, User::$rulesForRegisterStep1);
            return $this->respType('error', $messages);
        }
        try {
            if ($selfExclusion = ListSelfExclusion::validateSelfExclusion($inputs)) {
                Session::put('selfExclusion', $selfExclusion);

                Session::put('allowStep2', true);
                return $this->respType('error', 'Este jogador está auto-excluído!', [
                    'type' => 'redirect', 'redirect' => '/registar/step2'
                ]);
            }
        } catch (Exception $e) {
            // erro
            Session::put('allowStep2', true);
            return $this->respType('error', $e->getMessage(), [
                'type' => 'redirect', 'redirect' => '/registar/step2'
            ]);
        }

        $identityStatus = 'waiting_confirmation';
        try {
            $cc = $inputs['document_number'];
            $cc = RulesValidator::CleanCC($cc);

            $nif = $inputs['tax_number'];
            $date = substr($inputs['birth_date'], 0, 10);
            $name = $inputs['fullname'];
            if (!$this->validaUser($cc, '0', $name, $date)) {
                Session::put('identity', true);
                $inputs['identity_checked'] = 0;
                $inputs['identity_method'] = 'none';
            } else {
                // clean CC
                $inputs['document_number'] = $cc;
                $identityStatus = 'confirmed';
                $inputs['identity_checked'] = 1;
                $inputs['identity_method'] = 'srij';
            }
        } catch (Exception $e){
            Session::put('identity', true);
            $inputs['identity_checked'] = 0;
            $inputs['identity_method'] = 'none';

            Log::error("SRIJ Validation Fail:_". $e->getMessage());
        }

        $user = new User;
        try {
            if (!$userSession = $user->signUp($inputs, function(User $user) use($identityStatus, $inputs) {
                /* Save Doc */
                if (isset($inputs['bank_iban'])) {
                    $inputs['bank_iban'] = mb_strtoupper(str_replace(' ', '', $inputs['bank_iban']));
                }
                if (!empty($inputs['bank_name']) && !empty($inputs['bank_iban'])) {
                    if (! $user->createBankAndIban([ // remap to this controller
                        'bank' => $inputs['bank_name'],
                        'bic' => $inputs['bank_bic'],
                        'iban' => $inputs['bank_iban'],
                    ], null, false)) {
                        return false;
                    }
                }
                /* Create User Status */
                return $user->setStatus($identityStatus, 'identity_status_id');
            })) {
                return $this->respType('error', 'Ocorreu um erro ao gravar os dados!');
            }
        } catch (Exception $e) {
            return $this->respType('error', trans($e->getMessage()));
        }
        Auth::login($user);
        Session::put('user_login_time', Carbon::now()->getTimestamp());
        /* Log user info in User Session */
        $userInfo = $this->request->server('HTTP_USER_AGENT');
        if (! $userSession = $user->logUserSession('user_agent', $userInfo)) {
            Auth::logout();
            return $this->respType('error', 'De momento não é possível efectuar login,<br> contudo a sua conta foi criada com sucesso, por favor tente fazer login mais tarde.', [
                'type' => 'login_error'
            ]);
        }

        Session::put('allowStep2', true);
        return $this->respType('success', 'Dados guardados com sucesso!', [
            'type' => 'redirect', 'redirect' => '/registar/step2'
        ]);
    }
    /**
     * Step 2 of user's registration process
     *
     * @return JsonResponse|\Illuminate\Http\RedirectResponse|View
     */
    public function registarStep2()
    {
        $allowStep2 = Session::get('allowStep2', false);
        $user = Auth::user();
        if (!$allowStep2)
            return $this->respType('error', 'Step 1', ['type' => 'redirect', 'redirect' => '/registar/step1']);

        $selfExclusion = Session::get('selfExclusion', false);
        if ($selfExclusion)
            return view('portal.sign_up.step_2', compact('selfExclusion'));
        /*
        * Validar identidade
        */
        if ($user === null)
            return $this->respType('error', 'Step 1', ['type' => 'redirect', 'redirect' => '/registar/step1']);

        $identity = Session::get('identity', false);
        if ($identity) {
            Session::put('allowStep2Post', true);
            return view('portal.sign_up.step_2', compact('identity'));
        }

        $token = str_random(10);
        Cache::add($token, $user->id, 30);
        Session::put('user_id', $user->id);
        Session::put('allowStep3', true);
        return $this->respType('success', 'Step 3', ['type' => 'redirect', 'redirect' => '/registar/step3']);
    }
    /**
     * Step 2 of user's registration process
     *
     * @return JsonResponse
     */
    public function registarStep2Post()
    {
        $file = $this->request->file('upload');
        $user = Auth::user();

        if (!Session::get('allowStep2', false))
            return $this->respType('empty', 'Redirecionar para ínicio', [
                'type' => 'redirect', 'redirect' => '/registar/step1'
            ]);

        if (!Session::get('allowStep2Post', false))
            return $this->respType('empty', 'Redirecionar para validar', [
                'type' => 'redirect', 'redirect' => '/registar/step2'
            ]);

        /*
        * Guardar comprovativo de identidade
        */
        $erro = null;
        if ($file === null) {
            return $this->respType('error', 'Ocorreu um erro a enviar o documento, por favor tente novamente.');
        }

        if (! $file->isValid()){
            return $this->respType('error', 'Ocorreu um erro a enviar o documento, por favor tente novamente.');
        }

        if ($file->getClientSize() >= $file->getMaxFilesize() || $file->getClientSize() > 5000000){
            return $this->respType('error', 'O tamanho máximo aceite é de 5mb.');
        }

        if ($doc = $user->addDocument($file, DocumentTypes::$Identity)){
            /* Create User Status */
            $user->setStatus('waiting_confirmation', 'identity_status_id');
        }

        Session::put('allowStep3', true);
        return $this->respType('success', 'Documento gravado com sucesso.', [
            'title' => 'Comprovativo de Identidade',
            'type' => 'redirect', 'redirect' => '/registar/step3'
        ]);
    }

    /**
     * Step 3 of user's registration process
     *
     * @return Response|string
     */
    public function registarStep3()
    {
        if (!Session::get('allowStep2', false))
            return $this->respType('error', 'Step 1', ['type' => 'redirect', 'redirect' => '/registar/step1']);

        if (!Session::get('allowStep3', false))
            return $this->respType('error', 'Step 2', ['type' => 'redirect', 'redirect' => '/registar/step2']);

        if (!Auth::check()) {
            // redirect back users from regist page.
            return "<script>top.location.href = '/';</script>";
        }

        /*
        * Validar user com identidade valida
        */
        $canDeposit = $this->authUser->checkCanDeposit();

        return View::make('portal.sign_up.step_3', compact('canDeposit'));
    }
    /**
     * Handle Post Login
     *
     * @return JsonResponse
     */
    public function postLogin()
    {
        $inputs = $this->request->only(['username', 'password']);
        $user = User::findByUsername($inputs['username']);

        if ($user) {
            $blockTime = config('app.block_user_time');
            $checkTime = Carbon::now()
                ->subMinutes($blockTime)
                ->tz('UTC');
            $FailedLogins = UserSession::query()
                ->where('user_id','=',$user->id)
                ->where('session_type','=','login_fail')
                ->where('created_at','>', $checkTime)
                ->get();

            $lastSession = $user->getLastSession()->created_at;

            if (($FailedLogins->count() >= 5) and $lastSession < $FailedLogins->last()->created_at) {
                $type = SendMail::$TYPE_11_LOGIN_FAIL;
                $mailSent = UserMail::query()
                    ->where('user_id', '=', $user->id)
                    ->where('type', '=', $type)
                    ->where('created_at', '>', $type)
                    ->first()
                ;
                if ($mailSent === null) {
                    /*
                    * Enviar email de tentativa de acesso
                    */
                    $mail = new SendMail($type);
                    $mail->prepareMail($user, [
                        'title' => 'Tentativa de login falhada',
                        'time' => $blockTime,
                    ]);
                    $mail->Send(false);
                }

                return $this->respType('error', "Conta Bloqueada por $blockTime minutos", [
                    'title' => 'Login',
                    'type' => 'login_error'
                ]);
            }
        }

        if (empty($inputs['username']) || empty($inputs['password']))
            return $this->respType('error', 'Preencha o nome de utilizador e a password!', [
                'title' => 'Login',
                'type' => 'login_error'
            ]);
        if (!Auth::attempt(['username' => $inputs['username'], 'password' => $inputs['password']])) {
            if ($user !== null) {
                $userInfo = $this->request->server('HTTP_USER_AGENT');
                $us = $user->logUserSession('login_fail', $userInfo);
            }

            return $this->respType('error', 'Nome de utilizador ou password inválidos!', [
                'title' => 'Login',
                'type' => 'login_error'
            ]);
        }

        $user = Auth::user();
        $us = $user->logoutOldSessions();
        $lastSession = $us->created_at;
        Session::flash('lastSession', $lastSession);
        Session::put('user_login_time', Carbon::now()->getTimestamp());

        /*
        * Validar autoexclusão
        */
        $msg = $user->checkSelfExclusionStatus();
        /* Create new User Session */
        if (!$userSession = $user->logUserSession('login', $msg, true)) {
            Auth::logout();
            return $this->respType('error', 'De momento não é possível efectuar login, por favor tente mais tarde.', [
                'title' => 'Login',
                'type' => 'login_error'
            ]);
        }
        /* Log user info in User Session */
        $userInfo = $this->request->server('HTTP_USER_AGENT');
        if (!$userSession = $user->logUserSession('user_agent', $userInfo)) {
            Auth::logout();
            return $this->respType('error', 'De momento não é possível efectuar login, por favor tente mais tarde.', [
                'title' => 'Login',
                'type' => 'login_error'
            ]);
        }
        if ($user->status->status_id === 'canceled'
            && ($user->balance->balance_available + $user->balance->balance_accounting) <= 0
        ) {
            Auth::logout();
            return $this->respType('error', 'A sua conta está cancelada.', [
                'title' => 'Login',
                'type' => 'login_error'
            ]);
        }
        return $this->respType('empty', 'Login efetuado com sucesso.', [
            'title' => 'Login',
            'type' => 'refresh'
        ]);
    }
    /**
     * Logout
     *
     * @return Response
     */
    public function getLogout()
    {
        if ($this->authUser != null) {
            $this->authUser->logUserSession('logout', 'Logged out');
            Session::flush();
            Auth::logout();
        }
        return Redirect::back()->with('message','Operation Successful !');
    }

    /**
     * Handle Recover password
     *
     * @param TokenRepositoryInterface $tokens
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function recuperarPasswordPost(TokenRepositoryInterface $tokens)
    {
        $email = $this->request->get('reset_email', null);
        if ($email === null)
            return $this->respType('error', 'Por favor preencha um Email!');

        $user = User::findByEmail($email);
        if ($user === null)
            return $this->respType('error', 'Esta conta não existe!');

        $tokens->create($user);
        $reset = PasswordReset::where('email','=',$user->getEmailForPasswordReset())
            ->where('created_at','>', Carbon::now()->subHour(1))
            ->first();

        try {
            $userSession = $user->logUserSession('reset_password', 'Reset password');

            $mail = new SendMail(SendMail::$TYPE_10_RESET_PASSWORD);
            $mail->prepareMail($user, [
                'title' => 'Recuperação de Palavra-passe',
                'url' => '/nova_password/' . $reset->token,
            ], $userSession->id);
            $mail->Send(true);
        } catch (Exception $e) {
            return $this->respType('error', 'Ocorreu um erro a enviar a mensagem!');
        }

        return $this->respType('success', 'Foi-lhe enviada uma mensagem para repor a Palavra Passe.');
    }

    public function novaPassword($token)
    {
        $reset = PasswordReset::where('token','=',$token)
            ->where('created_at','>', Carbon::now()->subHour(1))
            ->first();

        if($reset)
        {
            $user = User::findByEmail($reset->email);
            return View::make('portal.novapassword',[
                'id'=> $user->id,
                'email' => $reset->email,
                'token' => $reset->token,
            ]);
        }
        return $this->respType('error', 'Token não encontrado!', [
            'type' => 'redirect',
            'redirect' => '/'
        ]);
    }
    public function novaPasswordPost(Request $request)
    {
        $inputs = $request->only(['id', 'mail_token', 'password']);
        /** @var PasswordReset $reset */
        $reset = PasswordReset::where('token','=',$inputs['mail_token'])
            ->where('created_at','>', Carbon::now()->subHour(1))
            ->first();

        if ($reset !== null) {
            $user = User::findById($inputs['id']);
            if ($user !== null && $user->profile->email === $reset->email) {
                $user->password = password_hash($inputs['password'],1);
                $user->save();
                PasswordReset::where('email', '=', $reset->email)->delete();
            }

            return $this->respType('success', 'Alterado a palavra-pass com sucesso!', [
                'type' => 'redirect',
                'redirect' => '/'
            ]);
        }
        return $this->respType('error', 'Token não encontrado!', [
            'type' => 'redirect',
            'redirect' => '/'
        ]);
    }

    /**
     * Get a Token to validate the user
     *
     * @param Response $response
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getToken(Response $response, Request $request)
    {
        // grab credentials from the request
        $credentials = $this->request->only('username', 'password');
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                $user = User::findByUsername($credentials['username']);
                if ($user !== null) {
                    $userInfo = $this->request->server('HTTP_USER_AGENT');
                    $us = $user->logUserSession('login_fail', $userInfo);
                    /*
                    * Enviar email de tentativa de acesso
                    */
                    $mail = new SendMail(SendMail::$TYPE_11_LOGIN_FAIL);
                    $mail->prepareMail($user, [
                        'title' => 'Tentativa de login falhada',
                        'ip' => $us->ip,
                        'dados' => $us->description,
                    ], $us->id);
                    $mail->Send(false);
                }
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
            $user = Auth::user();
            Session::put('user_login_time', Carbon::now()->getTimestamp());
            $us = $user->logoutOldSessions();
            $lastSession = $us->created_at;
            /*
            * Validar autoexclusão
            */
            $msg = $user->checkSelfExclusionStatus();
            /* Create new User Session */
            if (! $userSession = $user->logUserSession('login', $msg, true)) {
                Auth::logout();
                return Response::json(array('status' => 'error', 'type' => 'login_error' ,'msg' => 'De momento não é possível efectuar login, por favor tente mais tarde.'));
            }
            /* Log user info in User Session */
            $userInfo = $this->request->server('HTTP_USER_AGENT');
            if (! $userSession = $user->logUserSession('user_agent', $userInfo)) {
                Auth::logout();
                return Response::json(array('status' => 'error', 'type' => 'login_error' ,'msg' => 'De momento não é possível efectuar login, por favor tente mais tarde.'));
            }
            if ($user->status->status_id === 'canceled'
                && ($user->balance->balance_available + $user->balance->balance_accounting) <= 0
            ) {
                Auth::logout();
                return Response::json(array('status' => 'error', 'type' => 'login_error',
                    'msg' => 'A sua conta está cancelada.'));
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        // all good so return the token
        return response()->json(compact('token','lastSession'));
    }

    public function postApiCheck()
    {
        $keys = ['email', 'username', 'tax_number', 'document_number'];
        $inputs = $this->request->only($keys);

        $rules = array_intersect_key(User::$rulesForRegisterStep1, array_flip($keys));
        foreach ($rules as $key => $rule) {
            if (is_array($rule)){
                $rules[$key] = array_diff($rule, ['required']);
            } else {
                $rules[$key] = str_replace('required|', '', $rule);
            }
        }
        $validator = Validator::make($inputs, $rules, User::$messagesForRegister);
        if ($validator->fails()) {
            return Response::json($validator->messages()->first());
        }
        return Response::json( 'true' );
    }

    public function confirmEmail(){
        $email = $this->request->get('email');
        $token = $this->request->get('token');

        if (empty($email) || empty($token)){
            // no input, just redirect...
            return Response::redirectTo('/');
        }

        $user = new User;
        try {
            $user->confirmEmail($email, $token);
        } catch (Exception $e) {
            if ($e->getMessage() === 'errors.email_already_checked') {
                if ($this->authUser !== null && $this->authUser->id === $user->id && !$this->authUser->identity_checked) {
                    return $this->respType('error', 'Confirme a sua Identidade!', [
                        'type' => 'redirect',
                        'redirect' => '/perfil/autenticacao'
                    ]);
                }
            }

            return $this->respType('error', trans($e->getMessage()), [
                'type' => 'redirect',
                'redirect' => '/'
            ]);
        }

        return $this->respType('success', 'O seu email foi confirmado com sucesso.', [
            'type' => 'redirect',
            'redirect' => '/'
        ]);
    }

    private function validaUser($cc, $nif, $name, $date){
        if (!env('SRIJ_WS_ACTIVE', false)) {
            return ListIdentityCheck::validateIdentity([
                'tax_number' => $nif,
                'name' => $name,
                'birth_date' => $date,
            ]);
        }
        $ws = new VerificacaoIdentidade(['exceptions' => true,]);
        /**
         * 0 - BI (ID CARD)
         * 1 - CARTAO_CIDADAO (CITIZEN CARD)
         * 2 - PASSAPORTE (PASSPORT)
         * 3 - NUMERO IDENTIFIC FISCAL (TAX IDENTIFICATION NUMBER)
         * 4 - OUTRO (OTHER)
         */
        $tipo = 1;

        $part = new PedidoVerificacaoTPType(config('app.srij_company_code'), $name, $cc, $tipo, $date, $nif);
        $identity = $ws->verificacaoidentidade($part);
        Log::info('VIdentidade', compact('name', 'cc', 'tipo', 'date', 'nif', 'identity'));
        if (!$identity->Sucesso){
            throw new Exception($identity->MensagemErro, $identity->CodigoErro, $identity->DetalheErro);
        }
        return $identity->Valido === 'S';
    }
}
