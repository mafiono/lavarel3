<?php
namespace App\Http\Controllers;
use App\Enums\DocumentTypes;
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
    protected $request;
    protected $authUser;
    protected $betConstruct;
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(Request $request, BetConstructApi $betConstruct)
    {
        $this->request = $request;
        $this->authUser = Auth::user();
        $this->betConstruct = $betConstruct;
        View::share('authUser', $this->authUser, 'request', $request);
    }
    /**
     * Step 1 of user's registration process
     *
     * @return Response
     */
    public function registarStep1()
    {
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
        return View::make('portal.sign_up.step_1', compact('inputs', 'countryList', 'natList', 'sitProfList'));
    }
    /**
     * Handle POST for Step1
     *
     * @return Response
     */
    public function registarStep1Post()
    {
        $inputs = $this->request->all();
        $inputs['birth_date'] = $inputs['age_year'].'-'.sprintf("%02d", $inputs['age_month']).'-'.sprintf("%02d",$inputs['age_day']);
        $sitProf = $inputs['sitprofession'];
//        if (in_array($sitProf, ['44','55','77','88'])){
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
        $inputs['profession'] = $sitProfList[$sitProf];
//        }else{
//            $inputs['profession'] = $inputs['sitprofession'];
//        }

        $validator = Validator::make($inputs, User::$rulesForRegisterStep1, User::$messagesForRegister);
        if ($validator->fails()) {
            $messages = User::buildValidationMessageArray($validator);
            dd($messages, $validator->errors());
            return Response::json( [ 'status' => 'error', 'msg' => $messages ] );
        }
        $user = new User;
        try {
            if (!$userSession = $user->signUp($inputs, function(User $user)  {
                /* Save Doc */


                /* Create User Status */
                return $user->setStatus('waiting_confirmation', 'identity_status_id');
            })) {
                return Response::json(array('status' => 'error', 'type' => 'error' ,'msg' => 'Ocorreu um erro ao gravar os dados!'));
            }
        } catch (Exception $e) {
            return Response::json(array('status' => 'error', 'type' => 'error' ,'msg' => trans($e->getMessage())));
        }
        /* Log user info in User Session */
        $userInfo = $this->request->server('HTTP_USER_AGENT');
        if (! $userSession = $user->logUserSession('user_agent', $userInfo)) {
            Auth::logout();
            return Response::json(array('status' => 'error', 'type' => 'login_error' ,'msg' => 'De momento não é possível efectuar login, por favor tente mais tarde.'));
        }
        Session::put('inputs', $inputs);
        Session::flash('success', 'Dados guardados com sucesso!');
        return Response::json( [ 'status' => 'success', 'type' => 'redirect', 'redirect' => '/registar/step2' ] );
    }
    /**
     * Step 2 of user's registration process
     *
     * @return Response
     */
    public function registarStep2()
    {
        if (!Session::has('inputs'))
            return redirect()->intended('/registar/step1');
        $inputs = Session::get('inputs');
        try {
            $selfExclusion = ListSelfExclusion::validateSelfExclusion($inputs);
            if ($selfExclusion) {
                Session::put('selfExclusion', $selfExclusion);
                View::make('portal.sign_up.step_2', compact('selfExclusion'));
            }
        } catch (Exception $e) {
            // erro 
        }
        /*
        * Validar identidade
        */
        $user = User::findByEmail($inputs['email']);
        try {
            $nif = $inputs['tax_number'];
            $date = substr($inputs['birth_date'], 0, 10);
            $name = $inputs['name'];
            if (!$this->validaUser($nif, $name, $date)){
                return View::make('portal.sign_up.step_2', [ 'identity' => true ]);
            }
        } catch (Exception $e){
            return View::make('portal.sign_up.step_2', [ 'identity' => true ])
                ->with('error', $e->getMessage());
        }
//        if (! ListIdentityCheck::validateIdentity($inputs)) {
//            Session::put('identity', true);
//            return View::make('portal.sign_up.step_2', [ 'identity' => true ]);
//        }


        $token = str_random(10);
        Cache::add($token, $user->id, 30);
        Session::put('user_id', $user->id);
        Auth::login($user);
        return view('portal.sign_up.step_3', compact('user','token'));


    }
    /**
     * Step 2 of user's registration process
     *
     * @return Response
     */
    public function registarStep2Post(Request $request)
    {
        dd('Her3e');
        $inputs = Session::get('inputs');
        $file = $request->file('upload');
        $user = User::findByEmail($inputs['email']);

        dd('Here1');
        if (!Session::has('inputs'))
            return Response::json(array('status' => 'error', 'type' => 'redirect', 'redirect' => '/registar/step1'));
        $inputs = Session::get('inputs');
        /*
        * Validar auto-exclusão
        */
        $selfExclusion = ListSelfExclusion::validateSelfExclusion($inputs);
        if ($selfExclusion) {
            Session::put('selfExclusion', $selfExclusion);
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Motivo: Autoexclusão. Data de fim:' . $selfExclusion->end_date]]);
        }
        dd('Here');
        /*
        * Guardar comprovativo de identidade
        */
        if (! $file->isValid())
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']]);

        if ($file->getClientSize() >= $file->getMaxFilesize() || $file->getClientSize() > 5000000)
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'O tamanho máximo aceite é de 5mb.']]);

        if ($doc = $user->addDocument($file, DocumentTypes::$Identity))

            /* Create User Status */
            $user->setStatus('waiting_confirmation', 'identity_status_id');

        $token = str_random(10);
        Cache::add($token, $user->id, 30);
        Session::put('user_id', $user->id);
        Session::put('user_id', $user->id);
        Auth::login($user);
        return view('portal.sign_up.step_3', compact('user', 'token'));
    }

    /**
     * Step 3 of user's registration process
     *
     * @return Response
     */
    public function registarStep3()
    {
       // if (!Session::has('user_id') || Session::has('selfExclusion') || Session::has('identity'))
            //return redirect()->intended('/registar/step1');

        return View::make('portal.sign_up.step_3');
    }
    /**
     * Handle POST for Step3
     *
     * @return Response
     */
    public function registarStep3Post()
    {
        if (Auth::user() || Session::has('selfExclusion') || Session::has('identity'))
            return Response::json(array('status' => 'error', 'type' => 'redirect' ,'msg' => 'Ocorreu um erro ao obter os dados!' ,'redirect' => '/registar/step1'));

        $inputs = $this->request->all();
        $validator = Validator::make($inputs, User::$rulesForRegisterStep3, User::$messagesForRegister);
        if ($validator->fails()) {

            return Response::json( [ 'status4' => 'success', 'msg' => "Falta validar Banco e Iban" ] );
        }
        /* @var $user User */
        $user = User::find(Session::get('user_id'));
        $userSession = Session::get('user_session');

        /* Save file */
       /* if (! $this->request->file('upload')->isValid())
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']]);

        $file = $this->request->file('upload');

        if ($file->getClientSize() >= $file->getMaxFilesize() || $file->getClientSize() > 5000000)
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'O tamanho máximo aceite é de 5mb.']]);

        if (! $doc = $this->authUser->addDocument($file, DocumentTypes::$Bank))
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']]);

        DB::beginTransaction();
        if (!$user->createBankAndIban($inputs, $doc) || !$user->setStatus('waiting_confirmation', 'iban_status_id')) {
            DB::rollback();
            return Response::json(array('status' => 'error', 'type' => 'error' ,'msg' => 'Ocorreu um erro ao gravar os dados!'));
        }
        /* Registar utilizador na BetConstruct*/
       /* if (!$this->betConstruct->signUp($user))
            return Response::json(array('status' => 'error', 'type' => 'error' ,'msg' => 'Ocorreu um erro ao gravar os dados, por favor tente mais tarde!'));
        DB::commit();
        Session::forget('user_id');
        /*
        * Autenticar o utilizador
        */

        Auth::login($user);
        return Response::json(array('status4' => 'success', 'type' => 'redirect' ,'redirect' => '/registar/step4'));
    }
    /**
     * Step 4 of user's registration process
     *
     * @return Response
     */
    public function registarStep4()
    {
        if (!Auth::check() || Session::has('selfExclusion') || Session::has('identity'))
            return redirect()->intended('/registar/step1');

        return View::make('portal.sign_up.step_4');
    }
    /**
     * Handle Post Login
     *
     * @return Response
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
                    ->subMinutes(30)->toDateTimeString())
                ->get();

            $lastSession = $user->getLastSession()->created_at;

            if (($FailedLogins->count() >= 5) and $lastSession < $FailedLogins->last()->created_at) {
                return Response::json(array('status' => 'error', 'type' => 'login_error', 'msg' => 'Conta Bloqueada por 30minutos'));
            }
        }

        if (empty($inputs['username']) || empty($inputs['password']))
            return Response::json(array('status' => 'error', 'type' => 'login_error', 'msg' => 'Preencha o nome de utilizador e a password!'));
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
                            $m->to($user->profile->email, $user->profile->name)->subject('BetPortugal - Tentativa de Acesso a sua Conta!');
                        });
                } catch (Exception $e) {
                    //do nothing..
                }
            }
            return Response::json(array('status' => 'error', 'type' => 'login_error', 'msg' => 'Nome de utilizador ou password inválidos!'));
        }

        $user = Auth::user();
        $us = $this->logoutOldSessions($user);
        $lastSession = $us->created_at;
        Session::flash('lastSession', $lastSession);

        /*
        * Validar auto-exclusão
        */
        $msg = $user->checkSelfExclusionStatus();
        /* Create new User Session */
        if (!$userSession = $user->logUserSession('login', $msg, true)) {
            Auth::logout();
            return Response::json(array('status' => 'error', 'type' => 'login_error',
                'msg' => 'De momento não é possível efectuar login, por favor tente mais tarde.'));
        }
        /* Log user info in User Session */
        $userInfo = $this->request->server('HTTP_USER_AGENT');
        if (!$userSession = $user->logUserSession('user_agent', $userInfo)) {
            Auth::logout();
            return Response::json(array('status' => 'error', 'type' => 'login_error',
                'msg' => 'De momento não é possível efectuar login, por favor tente mais tarde.'));
        }
        if ($user->status->status_id === 'canceled'
            && ($user->balance->balance_available + $user->balance->balance_accounting) <= 0
        ) {
            Auth::logout();
            return Response::json(array('status' => 'error', 'type' => 'login_error',
                'msg' => 'A sua conta está cancelada.'));
        }
        return Response::json(array('status' => 'success', 'type' => 'reload'));
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
     * Recover password
     *
     * @return Response
     */
    public function recuperarPassword()
    {
        return View::make('portal.sign_up.reset_password');
    }
    public function resetPassword()
    {

    }
    /**
     * Handle Recover password
     *
     * @return Response
     */
    public function recuperarPasswordPost(TokenRepositoryInterface $tokens)
    {
        $inputs = $this->request->only(['reset_email']);

        $user = User::findByEmail($inputs['reset_email']);
        $tokens->create($user);
        $reset = PasswordReset::where('email','=',$user->getEmailForPasswordReset())->where('created_at','>',Carbon::now()->subhour(1))->first();
        try {
            Mail::send('portal.sign_up.emails.reset_password', ['username' => $user->username,'token'=>$reset->token], function ($m) use ($user) {
                $m->to($user->profile->email)->subject('BetPortugal - Recuperação de Password!');
            });
        } catch (Exception $e) {
            //do nothing..
        }

        return Response::json( [ 'status' => 'success','message' => 'Email enviado' ,'type' => 'redirect', 'redirect' => '/' ] );
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
                $m->to($user->profile->email, $user->profile->name)->subject('BetPortugal - Recuperação de Password!');
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
                                $m->to($user->profile->email, $user->profile->name)->subject('BetPortugal - Tentativa de Acesso a sua Conta!');
                            });
                    } catch (Exception $e) {
                        //do nothing..
                    }
                }
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
            $user = Auth::user();
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
        $inputs = $this->request->only('username', 'email');

        $validator = Validator::make($inputs, [
            'email' => 'email|unique:user_profiles,email',
            'username' => 'unique:users,username',
        ],[
            'email.email' => 'Insira um email válido',
            'email.unique' => 'Email já se encontra registado',
            'username.unique' => 'Nome de Utilizador Indisponivel',
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

    public function concluiRegisto($token){
        $id = Cache::get($token);
        $user = User::findById($id);
        Auth::loginUsingId($id);
        $userInfo = $this->request->server('HTTP_USER_AGENT');
        if ( $userSession = $user->logUserSession('user_agent', $userInfo)) {
            Cache::forget($token);
            return redirect('/');
        }

    }

    private function validaUser($nif, $name, $date){
        if (!env('SRIJ_WS_ACTIVE', false)) {
            return false;
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
