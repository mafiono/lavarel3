<?php

namespace App\Http\Controllers\Portal;

use App\Enums\DocumentTypes;
use App\Http\Controllers\Controller;
use App\Models\Country;
use Session, View, Response, Auth, Mail, Validator, Hash;
use Illuminate\Http\Request;
use App\User, App\UserDocument;

class ProfileController extends Controller
{

    protected $authUser;

    protected $userSessionId;

    protected $request;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth', ['except' => 'definicoes']);

        $this->request = $request;
        $this->authUser = Auth::user();
        $this->userSessionId = Session::get('user_session');

        View::share('authUser', $this->authUser, 'request', $request);
    }

    /**
     * Display user profile page
     *
     * @return \View
     */
    public function profile()
    {
        $countryList = Country::query()
            ->where('cod_num', '>', 0)
            ->orderby('name')->lists('name','cod_alf2')->all();
        return view('portal.profile.personal_info',compact('countryList'));
    }

    /**
     * Handle profile POST
     *
     * @return array Json array
     */
    public function profilePost()
    {
        $inputs = $this->request->only('profession','country', 'address', 'city', 'zip_code', 'phone');

        $validator = Validator::make($inputs, User::$rulesForUpdateProfile, User::$messagesForRegister);
        if ($validator->fails()) {
            $messages = User::buildValidationMessageArray($validator);
            return Response::json( [ 'status' => 'error', 'msg' => $messages ] );
        }

        /* Check if there is changes in Morada */
        $profile = $this->authUser->profile;
        $moradaChanged = ($profile->country !== $inputs['country']
            || $profile->address !== $inputs['address']
            || $profile->city !== $inputs['city']
            || $profile->zip_code !== $inputs['zip_code']);

        if (! $this->authUser->updateProfile($inputs, $moradaChanged))
            return Response::json(['status' => 'error', 'type' => 'error',
                'msg' => 'Ocorreu um erro ao atualizar os dados do seu perfil, por favor tente mais tarde.']);

        if ($moradaChanged) {
            /*
            * Guardar comprovativo de identidade
            */
            $file = $this->request->file('upload');
            if ($file == null || ! $file->isValid())
                return Response::json(['status' => 'error', 'msg' => ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']]);

            if ($file->getClientSize() >= $file->getMaxFilesize() || $file->getClientSize() > 5000000)
                return Response::json(['status' => 'error', 'msg' => ['upload' => 'O tamanho máximo aceite é de 5mb.']]);

            /* Save Doc */
            if (! $fullPath = $this->authUser->addDocument($file, DocumentTypes::$Address, $this->userSessionId))
                return Response::json(['status' => 'error', 'msg' => ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']]);
        }

        Session::flash('success', 'Perfil alterado com sucesso!');

        return Response::json(['status' => 'success', 'type' => 'reload']);
    }
    /**
     * Display user profile/authentication page
     *
     * @return \View
     */
    public function authentication()
    {
        $statusId = $this->authUser->status->identity_status_id;

        $docs = $this->authUser->findDocsByType(DocumentTypes::$Identity);

        return view('portal.profile.authentication', compact('statusId', 'docs'));
    }

    /**
     * Handle perfil autenticação POST
     *
     * @return array Json array
     */
    public function authenticationPost()
    {
        if (! $this->request->hasFile('upload'))
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Por favor escolha um documento a enviar.']]);

        if (! $this->request->file('upload')->isValid())
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']]);

        $file = $this->request->file('upload');

        if ($file->getClientSize() >= $file->getMaxFilesize() || $file->getClientSize() > 5000000)
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'O tamanho máximo aceite é de 5mb.']]);

        if (! $fullPath = $this->authUser->addDocument($file, DocumentTypes::$Identity, $this->userSessionId))
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']]);

       /*
        * Enviar email com o anexo
        */
        try {
            Mail::send('portal.profile.emails.authentication', ['user' => $this->authUser], function ($m) use ($fullPath) {
                $m->to(env('MAIL_USERNAME'), env('MAIL_NAME'))->subject('Autenticação de Identidade - Novo Documento');
                $m->cc(env('TEST_MAIL'), env('TEST_MAIL_NAME'));
                $m->attach($fullPath);
            });
        } catch (\Exception $e) {
            //goes silent
        }
        Session::flash('success', 'Documento enviado com sucesso!');

        return Response::json(['status' => 'success', 'type' => 'reload']);
    }

    public function addressAuthentication()
    {
        $docs = $this->authUser->findDocsByType(DocumentTypes::$Address);

        return view('portal.profile.address_authentication', compact('docs'));
    }

    public function addressAuthenticationPost()
    {
        if (! $this->request->hasFile('upload'))
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Por favor escolha um documento a enviar.']]);

        if (! $this->request->file('upload')->isValid())
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']]);

        $file = $this->request->file('upload');

        if ($file->getClientSize() >= $file->getMaxFilesize() || $file->getClientSize() > 5000000)
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'O tamanho máximo aceite é de 5mb.']]);

        if (! $fullPath = $this->authUser->addDocument($file, DocumentTypes::$Address, $this->userSessionId))
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']]);

        /*
         * Enviar email com o anexo
         */
        try {
            Mail::send('portal.profile.emails.authentication', ['user' => $this->authUser], function ($m) use ($fullPath) {
                $m->to(env('MAIL_USERNAME'), env('MAIL_NAME'))->subject('Autenticação de Morada - Novo Documento');
                $m->cc(env('TEST_MAIL'), env('TEST_MAIL_NAME'));
                $m->attach($fullPath);
            });
        } catch (\Exception $e) {
            //goes silent
        }

        Session::flash('success', 'Documento enviado com sucesso!');

        return Response::json(['status' => 'success', 'type' => 'reload']);
    }
    public function downloadAttachment()
    {
        $id = $this->request->get('id');
        if (! $id || $id <= 0)
            return Response::json(['status' => 'error', 'msg' => 'Id inválido']);

        $doc = $this->authUser->findDocById($id);
        if ($doc == null)
            return Response::json(['status' => 'error', 'msg' => 'Id inválido']);

        $file = storage_path()
            .DIRECTORY_SEPARATOR.'documentacao'
            .DIRECTORY_SEPARATOR.$doc->type
            .DIRECTORY_SEPARATOR.$doc->file;

        return Response::download($file, $doc->description);
    }

    /**
     * Display user profile/password page
     *
     * @return \View
     */
    public function passwordGet()
    {
        return view('portal.profile.password');
    }
    /**
     * Handle perfil password POST
     *
     * @return array Json array
     */
    public function passwordPost()
    {
        $inputs = $this->request->only('old_password','password', 'conf_password');

        if (! Hash::check($inputs['old_password'], $this->authUser->password))
            return Response::json( [ 'status' => 'error', 'msg' => ['old_password' => 'A antiga password introduzida não está correta.'] ] );

        $validator = Validator::make($inputs, User::$rulesForChangePassword, User::$messagesForRegister);
        if ($validator->fails()) {
            $messages = User::buildValidationMessageArray($validator);
            return Response::json( [ 'status' => 'error', 'msg' => $messages ] );
        }

        if (! $this->authUser->newPassword($inputs['password']))
            return Response::json(['status' => 'error', 'msg' => ['password' => 'Ocorreu um erro a alterar a password, por favor tente novamente.']]);

        Session::flash('success', 'Password alterada com sucesso!');

        return Response::json(['status' => 'success', 'type' => 'reload']);
    }
    /**
     * Display user profile/security-pin page
     *
     * @return \View
     */
    public function securityPinGet()
    {
        return view('portal.profile.security_pin');
    }
    /**
     * Handle perfil codigo pin POST
     *
     * @return array Json array
     */
    public function securityPinPost()
    {
        $inputs = $this->request->only('old_security_pin','security_pin', 'conf_security_pin');

        if ($inputs['old_security_pin'] != $this->authUser->security_pin)
            return Response::json( [ 'status' => 'error', 'msg' => ['old_security_pin' => 'O código de segurança antigo introduzido não está correto.'] ] );

        $validator = Validator::make($inputs, User::$rulesForChangePin, User::$messagesForRegister);
        if ($validator->fails()) {
            $messages = User::buildValidationMessageArray($validator);
            return Response::json( [ 'status' => 'error', 'msg' => $messages ] );
        }

        if (! $this->authUser->changePin($inputs['security_pin']))
            return Response::json(['status' => 'error', 'msg' => ['password' => 'Ocorreu um erro a alterar o pin, por favor tente novamente.']]);

        Session::flash('success', 'Código Pin alterado com sucesso!');

        return Response::json(['status' => 'success', 'type' => 'reload']);
    }
    /**
     * Display settings page
     *
     * @return \View
     */
    public function settings()
    {
        return view('portal.profile.settings');
    }
    /**
     * Handle get balance Ajax GET
     *
     * @return array Json array
     */
    public function getBalance()
    {
        return Response::json(['balance' => $this->authUser->balance->balance_available]);
    }
}
