<?php
namespace App\Http\Controllers;
use Auth, View, Validator, Response, Session, Hash, Mail, DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\User, App\ListSelfExclusion, App\ListIdentityCheck;
use App\Lib\BetConstructApi;
use Parser;
use App\ApiRequestLog;
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
        if (Session::has('jogador_id'))
            return redirect()->intended('/portal/registar/step3');
        $inputs = '';
        if(Session::has('inputs'))
            $inputs = Session::get('inputs');
        return View::make('portal.sign_up.step_1', compact('inputs'));
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
        if (!$userSession = $user->signUp($inputs, function(User $user, $id){
            /* Create User Status */
            return $user->setStatus('confirmed', 'identity_status_id', $id);
        })) {
            return Response::json(array('status' => 'error', 'type' => 'error', 'msg' => 'Ocorreu um erro ao gravar os dados!'));
        }
        Session::put('user_session', $userSession->id);
        Session::put('user_id', $user->id);
        Session::forget('inputs');
        Session::forget('selfExclusion');
        Session::forget('identity');
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
        if (!$userSession = $user->signUp($inputs, function(User $user, $id) use($file) {
            /* Save Doc */
            if (! $fullPath = $user->addDocument($file, 'compovativo_identidade', $id)) return false;

            /* Create User Status */
            return $user->setStatus('waiting_confirmation', 'identity_status_id', $id);
        })) {
            return Response::json(array('status' => 'error', 'type' => 'error' ,'msg' => 'Ocorreu um erro ao gravar os dados!'));
        }
        Session::put('user_session', $userSession->id);
        Session::put('user_id', $user->id);
        Session::forget('inputs');
        Session::forget('selfExclusion');
        Session::forget('identity');
        return Response::json(array('status' => 'success', 'type' => 'redirect','redirect' => '/registar/step3'));
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
        /* Save file */
        if (! $this->request->file('upload')->isValid())
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']]);

        $file = $this->request->file('upload');

        if ($file->getClientSize() >= $file->getMaxFilesize() || $file->getClientSize() > 5000000)
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'O tamanho máximo aceite é de 5mb.']]);

        if (! $fullPath = $this->authUser->addDocument($file, 'compovativo_iban', $this->userSessionId))
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']]);

        /* @var $user User */
        $user = User::find(Session::get('user_id'));
        $userSession = Session::get('user_session');
        DB::beginTransaction();
        if (!$user->createBankAndIban($inputs, $userSession) || !$user->setStatus('waiting_identity', $userSession)) {
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
        if (! $userSession = $user->createUserSession(['description' => 'login'], true)) {
            Auth::logout();
            return Response::json(array('status' => 'error', 'type' => 'login_error' ,'msg' => 'De momento não é possível efectuar login, por favor tente mais tarde.'));
        }
        Session::put('userSessionId', $userSession->id);
        /*
        * Validar auto-exclusão
        */
        $data['document_number'] = $user->profile->document_number;
        $selfExclusion = ListSelfExclusion::validateSelfExclusion($data);
        if ($selfExclusion) {
            // TODO rework this logic.
            // return Response::json(array('status' => 'error', 'type' => 'login_error' ,'msg' => 'O utilizador encontra-se autoexcluído.'));
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
        Session::flush();
        Auth::logout();
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
        } catch (\Exception $e) {
            //do nothing..
        }
        return Response::json( [ 'status' => 'success', 'type' => 'redirect', 'redirect' => '/' ] );
    }
    public function postApiLogin()
    {
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
