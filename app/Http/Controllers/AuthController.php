<?php
namespace App\Http\Controllers;
use App\Enums\DocumentTypes;
use App\Models\Country;
use Auth, View, Validator, Response, Session, Hash, Mail, DB;
use Exception;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\User, App\ListSelfExclusion, App\ListIdentityCheck;
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
        if (Auth::check())
            return redirect()->intended('/');

        if (Session::has('jogador_id'))
            return redirect()->intended('/portal/registar/step3');

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
            '99' => 'Outra',
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
        Session::forget('inputs');
        $inputs = $this->request->all();
        $inputs['birth_date'] = $inputs['age_year'].'-'.sprintf("%02d", $inputs['age_month']).'-'.sprintf("%02d",$inputs['age_day']);
        $sitProf = $inputs['sitprofession'];
        if (in_array($sitProf, ['44','55','77','88'])){
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
                '99' => 'Outra',
            ];
            $inputs['profession'] = $sitProfList[$sitProf];
        }

        $validator = Validator::make($inputs, User::$rulesForRegisterStep1, User::$messagesForRegister);
        if ($validator->fails()) {
            $messages = User::buildValidationMessageArray($validator);
            return Response::json( [ 'status' => 'error', 'msg' => $messages ] );
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
        /*
        * Validar auto-exclusão
        */
        $selfExclusion = ListSelfExclusion::validateSelfExclusion($inputs);
        if ($selfExclusion) {
            Session::put('selfExclusion', $selfExclusion);
            View::make('portal.sign_up.step_2', compact('selfExclusion'));
        }
        /*
        * Validar identidade
        */
        if (! ListIdentityCheck::validateIdentity($inputs)) {
            Session::put('identity', true);
            return View::make('portal.sign_up.step_2', [ 'identity' => true ]);
        }
        $user = new User;
        try{
            if (!$userSession = $user->signUp($inputs, function(User $user){
                /* Create User Status */
                return $user->setStatus('confirmed', 'identity_status_id');
            })) {
                return View::make('portal.sign_up.step_2')->with('error', 'Ocorreu um erro ao gravar os dados!');
            }
        } catch (Exception $e) {
            return View::make('portal.sign_up.step_2')->with('error', trans($e->getMessage()));
        }
        Session::put('user_id', $user->id);
        Session::forget('inputs');
        Session::forget('selfExclusion');
        Session::forget('identity');
        Auth::login($user);
        return View::make('portal.sign_up.step_2');
    }
    /**
     * Step 2 of user's registration process
     *
     * @return Response
     */
    public function registarStep2Post()
    {
        if (!Session::has('inputs'))
            return Response::json(array('status' => 'error', 'type' => 'redirect','redirect' => '/registar/step1'));
        $inputs = Session::get('inputs');
        /*
        * Validar auto-exclusão
        */
        $selfExclusion = ListSelfExclusion::validateSelfExclusion($inputs);
        if ($selfExclusion) {
            Session::put('selfExclusion', $selfExclusion);
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Motivo: Autoexclusão. Data de fim:' . $selfExclusion->end_date]]);
        }
        /*
        * Guardar comprovativo de identidade
        */
        if (! $this->request->file('upload')->isValid())
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']]);

        $file = $this->request->file('upload');

        if ($file->getClientSize() >= $file->getMaxFilesize() || $file->getClientSize() > 5000000)
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'O tamanho máximo aceite é de 5mb.']]);

        $user = new User;
        try {
            if (!$userSession = $user->signUp($inputs, function(User $user, $id) use($file) {
                /* Save Doc */
                if (! $fullPath = $user->addDocument($file, DocumentTypes::$Identity, $id)) return false;

                /* Create User Status */
                return $user->setStatus('waiting_confirmation', 'identity_status_id');
            })) {
                return Response::json(array('status' => 'error', 'type' => 'error' ,'msg' => 'Ocorreu um erro ao gravar os dados!'));
            }
        } catch (Exception $e) {
            return Response::json(array('status' => 'error', 'type' => 'error' ,'msg' => trans($e->getMessage())));
        }
        Session::put('user_id', $user->id);
        Session::forget('inputs');
        Session::forget('selfExclusion');
        Session::forget('identity');
        Auth::login($user);
        return Response::json(array('status' => 'success', 'type' => 'redirect','redirect' => '/registar/step4'));
    }
    /**
     * Step 3 of user's registration process
     *
     * @return Response
     */
    public function registarStep3()
    {
        if (!Session::has('user_id') || Session::has('selfExclusion') || Session::has('identity'))
            return redirect()->intended('/registar/step1');
        return View::make('portal.sign_up.step_3');
    }
    /**
     * Handle POST for Step3
     *
     * @return Response
     */
    public function registarStep3Post()
    {
        if (!Session::has('user_id') || Session::has('selfExclusion') || Session::has('identity'))
            return Response::json(array('status' => 'error', 'type' => 'redirect' ,'msg' => 'Ocorreu um erro ao obter os dados!' ,'redirect' => '/registar/step1'));

        $inputs = $this->request->all();
        $validator = Validator::make($inputs, User::$rulesForRegisterStep3, User::$messagesForRegister);
        if ($validator->fails()) {
            $messages = Jogadorconta::buildValidationMessageArray($validator);
            return Response::json( [ 'status' => 'error', 'msg' => $messages ] );
        }
        /* @var $user User */
        $user = User::find(Session::get('user_id'));
        $userSession = Session::get('user_session');

        /* Save file */
        if (! $this->request->file('upload')->isValid())
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']]);

        $file = $this->request->file('upload');

        if ($file->getClientSize() >= $file->getMaxFilesize() || $file->getClientSize() > 5000000)
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'O tamanho máximo aceite é de 5mb.']]);

        if (! $fullPath = $this->authUser->addDocument($file, DocumentTypes::$Bank, $userSession))
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']]);

        DB::beginTransaction();
        if (!$user->createBankAndIban($inputs, $userSession) || !$user->setStatus('waiting_confirmation', 'iban_status_id')) {
            DB::rollback();
            return Response::json(array('status' => 'error', 'type' => 'error' ,'msg' => 'Ocorreu um erro ao gravar os dados!'));
        }
        /* Registar utilizador na BetConstruct*/
        if (!$this->betConstruct->signUp($user))
            return Response::json(array('status' => 'error', 'type' => 'error' ,'msg' => 'Ocorreu um erro ao gravar os dados, por favor tente mais tarde!'));
        DB::commit();
        Session::forget('user_id');
        /*
        * Autenticar o utilizador
        */
        Auth::login($user);
        return Response::json(array('status' => 'success', 'type' => 'redirect' ,'redirect' => '/registar/step4'));
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
        if (empty($inputs['username']) || empty($inputs['password']))
            return Response::json(array('status' => 'error', 'type' => 'login_error' ,'msg' => 'Preencha o nome de utilizador e a password!'));
        if (! Auth::attempt(['username' => $inputs['username'], 'password' => $inputs['password']]))
            return Response::json(array('status' => 'error', 'type' => 'login_error' ,'msg' => 'Nome de utilizador ou password inválidos!'));

        $user = Auth::user();
        /* Create new User Session */
        if (! $userSession = $user->logUserSession('login', 'login', true)) {
            Auth::logout();
            return Response::json(array('status' => 'error', 'type' => 'login_error' ,'msg' => 'De momento não é possível efectuar login, por favor tente mais tarde.'));
        }
        /* Log user info in User Session */
        $userInfo = $this->request->server('HTTP_USER_AGENT');
        if (! $userSession = $user->logUserSession('user_agent', $userInfo)) {
            Auth::logout();
            return Response::json(array('status' => 'error', 'type' => 'login_error' ,'msg' => 'De momento não é possível efectuar login, por favor tente mais tarde.'));
        }
        /*
        * Validar auto-exclusão
        */
        $user->checkSelfExclusionStatus();
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
    /**
     * Handle Recover password
     *
     * @return Response
     */
    public function recuperarPasswordPost()
    {
        $inputs = $this->request->only(['reset_email']);

        $user = User::findByEmail($inputs['reset_email']);
        if (!$user)
            return Response::json( [ 'status' => 'error', 'msg' => ['email' => 'Utilizador inválido'] ] );
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
            Mail::send('portal.sign_up.emails.reset_password', ['username' => $user->username, 'password' => $password],
                function ($m) use ($user) {
                $m->to($user->profile->email, $user->profile->name)->subject('iBetUp - Recuperação de Password!');
            });
        } catch (Exception $e) {
            //do nothing..
        }
        return Response::json( [ 'status' => 'success', 'type' => 'redirect', 'redirect' => '/' ] );
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
                $m->to($user->profile->email, $user->profile->name)->subject('iBetUp - Recuperação de Password!');
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
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
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

}
