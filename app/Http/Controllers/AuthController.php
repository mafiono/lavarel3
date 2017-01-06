<?php
namespace App\Http\Controllers;
use App\Enums\DocumentTypes;
use App\Http\Traits\GenericResponseTrait;
use App\Lib\Captcha\SimpleCaptcha;
use App\Lib\IdentityVerifier\ListaVerificaIdentidade;
use App\Lib\IdentityVerifier\PedidoVerificacaoTPType;
use App\Models\Country;
use App\PasswordReset;
use App\UserSession;
use Auth, View, Validator, Response, Session, Hash, Mail, DB;
use Cache;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Passwords\PasswordResetServiceProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\User, App\ListSelfExclusion, App\ListIdentityCheck;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;
use App\Lib\BetConstructApi;
use Parser;
use App\ApiRequestLog;
use PayPal\Api\CountryCode;
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
            return "<script>top.location.href = '/';</script>";
        }

        $captcha = (new SimpleCaptcha('/captcha'))->generateCaptcha();
        Session::put('captcha', $captcha['session']);

        $countryList = array_merge(Country::query()->where('cod_alf2','=','PT')->lists('name','cod_alf2')->all(),  Country::query()
            ->where('cod_num', '>', 0)
            ->where('name','!=','Portugal')
            ->orderby('name')->lists('name','cod_alf2')->all());
        $natList = array_merge(Country::query()->where('cod_alf2','=','PT')->lists('nationality','cod_alf2')->all(), Country::query()
            ->where('cod_num', '>', 0)->whereNotNull('nationality')
            ->where('name','!=','Portugal')
            ->orderby('nationality')->lists('nationality','cod_alf2')->all());
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
            Session::put('error', $e->getMessage());
            Session::put('allowStep2', true);
            return $this->respType('error', $e->getMessage(), [
                'type' => 'redirect', 'redirect' => '/registar/step2'
            ]);
        }

        $identityStatus = 'waiting_confirmation';
        try {
            $nif = $inputs['tax_number'];
            $date = substr($inputs['birth_date'], 0, 10);
            $name = $inputs['name'];
            if (!$this->validaUser($nif, $name, $date)){
                Session::put('identity', true);
            } else {
                $identityStatus = 'confirmed';
            }
        } catch (Exception $e){
            Session::put('error', $e->getMessage());
            Session::put('allowStep2', true);

            return $this->respType('error', $e->getMessage(), [
                'type' => 'redirect', 'redirect' => '/registar/step2'
            ]);
        }

        $user = new User;
        try {
            if (!$userSession = $user->signUp($inputs, function(User $user) use($identityStatus) {
                /* Save Doc */

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
            return $this->respType('error', 'De momento não é possível efectuar login,<br> por favor tente mais tarde.', [
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
     * @return Response|View
     */
    public function registarStep2()
    {
        $user = Auth::user();
        if (!Session::get('allowStep2', false))
            return redirect()->intended('/registar/step1');

        $selfExclusion = Session::get('selfExclusion', false);
        if ($selfExclusion)
            return view('portal.sign_up.step_2', compact('selfExclusion'));
        /*
        * Validar identidade
        */
        if ($user === null)
            return redirect()->intended('/registar/step1');

        $identity = Session::get('identity', false);
        if ($identity) {
            Session::put('allowStep2Post', true);
            return view('portal.sign_up.step_2', compact('identity'));
        }

        $token = str_random(10);
        Cache::add($token, $user->id, 30);
        Session::put('user_id', $user->id);
        return view('portal.sign_up.step_3', compact('user','token'));
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
     * @return Response
     */
    public function registarStep3()
    {
        if (!Session::get('allowStep2', false))
            return redirect()->intended('/registar/step1');

        if (!Session::get('allowStep3', false))
            return redirect()->intended('/registar/step2');

        return View::make('portal.sign_up.step_3');
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
            $FailedLogins = UserSession::query()
                ->where('user_id','=',$user->id)
                ->where('session_type','=','login_fail')
                ->where('created_at','>',Carbon::now()
                    ->subMinutes(env('BLOCK_USER_TIME', 10))->toDateTimeString())
                ->get();

            $lastSession = $user->getLastSession()->created_at;

            if (($FailedLogins->count() >= 5) and $lastSession < $FailedLogins->last()->created_at) {
                return $this->respType('error', 'Conta Bloqueada por 30minutos', [
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

                /*
                * Enviar email de tentativa de acesso
                */
                try {
                    Mail::send('portal.mails.fail_login', ['username' => $user->username, 'dados' => $us->description, 'ip' => $us->ip],
                        function ($m) use ($user) {
                            $m->to($user->profile->email, $user->profile->name)->subject('CasinoPortugal - Tentativa de Acesso a sua Conta!');
                        });
                } catch (Exception $e) {
                    //do nothing..
                }
            }

            return $this->respType('error', 'Nome de utilizador ou password inválidos!', [
                'title' => 'Login',
                'type' => 'login_error'
            ]);
        }

        $user = Auth::user();
        $us = $this->logoutOldSessions($user);
        $lastSession = $us->created_at;
        Session::flash('lastSession', $lastSession);
        Session::put('user_login_time', Carbon::now()->getTimestamp());

        /*
        * Validar auto-exclusão
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
            'type' => 'reload'
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
        $reset = PasswordReset::where('email','=',$user->getEmailForPasswordReset())->where('created_at','>',Carbon::now()->subhour(1))->first();
        try {
            Mail::send('portal.sign_up.emails.reset_password', ['username' => $user->username,'token'=>$reset->token],
                function (Message $m) use ($user) {
                $m->to($user->profile->email)->subject(trans('name.brand') . ' - Recuperação de palavra passe!');
            });
        } catch (Exception $e) {
            return $this->respType('error', 'Ocorreu um erro a enviar a mensagem!');
        }

        return $this->respType('success', 'Foi-lhe enviada uma mensagem para repor a Palavra Passe.');
    }

    public function novaPassword($token)
    {
        $reset =  PasswordReset::where('token','=',$token)->where('created_at','>',Carbon::now()->subhour(1))->first();

        if($reset)
        {
            $user = User::findByEmail($reset->email);
            return View::make('portal.novapassword',['id'=> $user->id,'email' => $reset->email]);
        }
        return redirect('/');
    }
    public function novaPasswordPost(Request $request)
    {
        $inputs = $request->all();
        $user = User::findById($inputs['id']);
        $user->password = password_hash($inputs['password'],1);
        $user->save();
        return redirect('/');

    }
    private function recuperarPasswordPostOLDWAY()
    {
        $inputs = $this->request->only(['username', 'email', 'age_day', 'age_month', 'age_year', 'security_pin']);
        $inputs['birth_date'] = $inputs['age_year'].'-'.sprintf("%02d", $inputs['age_month']).'-'.sprintf("%02d",$inputs['age_day']).' 00:00:00';
        $user = User::findByUsername($inputs['username']);
        if (!$user)
            return Response::json( [ 'status' => 'error', 'msg' => ['username' => 'Utilizador inválido'] ] );
        if ($user->profile->email != $inputs['email'] ||
            $user->profile->birth_date != $inputs['birth_date'] ||
            $user->security_pin != $inputs['security_pin'])
            return Response::json( [ 'status' => 'error', 'msg' => ['username' => 'Os dados inseridos não estão correctos, por favor verifique todos os campos.'] ] );
        /*
        * Gerar nova password
        */
        $password = str_random(10);
        if (! $user->resetPassword($password))
            return Response::json( [ 'status' => 'error', 'msg' => ['username' => 'Ocorreu um erro ao recuperar a password.'] ] );
        /*
        * Enviar email de recuperação
        */
        try {
            Mail::send('portal.sign_up.emails.reset_password', ['username' => $user->username, 'password' => $password], function ($m) use ($user) {
                $m->to($user->profile->email, $user->profile->name)->subject('CasinoPortugal - Recuperação de Password!');
            });
        } catch (Exception $e) {
            //do nothing..
        }
        return Response::json( [ 'status' => 'success', 'type' => 'redirect', 'redirect' => '/' ] );
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
                    try {
                        Mail::send('portal.mails.fail_login', ['username' => $user->username, 'dados' => $us->description, 'ip' => $us->ip],
                            function ($m) use ($user) {
                                $m->to($user->profile->email, $user->profile->name)->subject('CasinoPortugal - Tentativa de Acesso a sua Conta!');
                            });
                    } catch (Exception $e) {
                        //do nothing..
                    }
                }
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
            $user = Auth::user();
            Session::put('user_login_time', Carbon::now()->getTimestamp());
            $us = $this->logoutOldSessions($user);
            $lastSession = $us->created_at;
            /*
            * Validar auto-exclusão
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
        $inputs = $this->request->only('username', 'email', 'tax_number');

        $validator = Validator::make($inputs, [
            'email' => 'email|unique:user_profiles,email',
            'username' => 'unique:users,username',
            'tax_number' => 'nif|digits_between:9,9|unique:user_profiles,tax_number',
        ],[
            'email.email' => 'Insira um email válido',
            'email.unique' => 'Email já se encontra registado',
            'username.unique' => 'Nome de Utilizador Indisponivel',
            'tax_number.nif' => 'Introduza um NIF válido',
            'tax_number.digits_between' => 'Este campo deve ter 9 digitos',
            'tax_number.unique' => 'Este NIF já se encontra registado',
        ]);
        if ($validator->fails()) {
            return Response::json( $validator->messages()->first());
        }
        return Response::json( 'true' );
    }

    public function confirmEmail(){
        $email = $this->request->get('email');
        $token = $this->request->get('token');

        if (empty($email) || empty($token)){
            return Response::redirectTo('/');
        }

        $user = new User;
        if (! $user->confirmEmail($email, $token)){
            return View::make('portal.sign_up.email_error');
        }

        return Response::redirectTo('/email_confirmado');
    }

    public function confirmedEmail(){
        return View::make('portal.sign_up.confirmed_email');
    }

    private function validaUser($nif, $name, $date){
        if (!env('SRIJ_WS_ACTIVE', false)) {
            return ListIdentityCheck::validateIdentity([
                'tax_number' => $nif,
                'name' => $name,
                'birth_date' => $date,
            ]);
        }
        $ws = new ListaVerificaIdentidade(['exceptions' => true,]);

        $part = new PedidoVerificacaoTPType($nif, $date);
        $part->setNome($name);
        $identity = $ws->verificacaoidentidade($part);
        if (!$identity->getSucesso()){
            throw new Exception($identity->getMensagemErro());
        }
        return $identity->getSucesso();
    }

    /**
     * @param $user
     * @return mixed
     */
    private function logoutOldSessions($user)
    {
        $us = $user->getLastSession();
        if (!in_array($us->session_type, ['logout', 'timeout'], true)) {
            $us->exists = false;
            $us->id = null;
            $time = Carbon::parse($us->created_at)->addMinutes(30);
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
}
