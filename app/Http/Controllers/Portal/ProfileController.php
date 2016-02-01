<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
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
        $this->userSessionId = Session::get('userSessionId');

        View::share('authUser', $this->authUser, 'request', $request);        
    }

    /**
     * Display user profile page
     *
     * @return \View
     */
    public function profile()
    {
        return view('portal.profile.personal_info');
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

        if ($moradaChanged) {
            /*
            * Guardar comprovativo de identidade
            */
            $file = $this->request->file('upload');
            if ($file == null || ! $file->isValid())
                return Response::json(['status' => 'error', 'msg' => ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']]);

            if ($file->getMimeType() != 'application/pdf')
                return Response::json(['status' => 'error', 'msg' => ['upload' => 'Apenas são aceites documentos no formato PDF.']]);

            if ($file->getClientSize() >= $file->getMaxFilesize() || $file->getClientSize() > 5000000)
                return Response::json(['status' => 'error', 'msg' => ['upload' => 'O tamanho máximo aceite é de 5mb.']]);

            /* Save Doc */
            if (! $fullPath = $this->authUser->addDocument($file, 'comprovativo_morada', $this->userSessionId))
                return Response::json(['status' => 'error', 'msg' => ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']]);
        }

        if (! $this->authUser->updateProfile($inputs, $this->userSessionId))
            return Response::json(['status' => 'error', 'type' => 'error',
                'msg' => 'Ocorreu um erro ao atualizar os dados do seu perfil, por favor tente mais tarde.']);

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
        return view('portal.profile.authentication');
    }

    public function addressAuthentication()
    {
        return view('portal.profile.address_authentication');
    }

    public function addressAuthenticationPost()
    {

        if (! $this->request->hasFile('upload'))
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Por favor escolha um documento a enviar.']]);

        if (! $this->request->file('upload')->isValid())
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']]);

        $file = $this->request->file('upload');
        if ($file->getMimeType() != 'application/pdf')
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Apenas são aceites documentos no formato PDF.']]);

        if ($file->getClientSize() >= $file->getMaxFilesize() || $file->getClientSize() > 5000000)
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'O tamanho máximo aceite é de 5mb.']]);

        if (! $fullPath = $this->authUser->addDocument($file, 'comprovativo_morada', $this->userSessionId))
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']]);

        $this->authUser->status->address_status_id = 'waiting_confirmation';
        $this->authUser->status->update();

        /*
         * Enviar email com o anexo
         */
        try {
            Mail::send('portal.profile.emails.authentication', ['user' => $this->authUser], function ($m) use ($fullPath) {
                $m->to('geral@ibetup.co.uk', 'iBetup')->subject('Autenticação de Morada - Novo Documento');
                $m->cc('luis.filipe.flima@gmail.com', 'Webhouse');
                $m->cc('miguel.teixeira@programmer.net', 'Webhouse');
                $m->attach($fullPath);
            });
        } catch (\Exception $e) {
            //goes silent
        }

        Session::flash('success', 'Documento enviado com sucesso!');

        return Response::json(['status' => 'success', 'type' => 'reload']);
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
        if ($file->getMimeType() != 'application/pdf')
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Apenas são aceites documentos no formato PDF.']]);

        if ($file->getClientSize() >= $file->getMaxFilesize() || $file->getClientSize() > 5000000)
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'O tamanho máximo aceite é de 5mb.']]);


        $this->authUser->status->identity_status_id = 'waiting_confirmation';
        $this->authUser->status->update();

        if (! $fullPath = $this->authUser->addDocument($file, 'comprovativo_identidade', $this->userSessionId))
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']]);

       /*
        * Enviar email com o anexo
        */
        try {
            Mail::send('portal.profile.emails.authentication', ['user' => $this->authUser], function ($m) use ($fullPath) {
                $m->to('geral@ibetup.co.uk', 'iBetup')->subject('Autenticação de Morada - Novo Documento');
                $m->cc('luis.filipe.flima@gmail.com', 'Webhouse');
                $m->cc('miguel.teixeira@programmer.net', 'Webhouse');
                $m->attach($fullPath);
            });
        } catch (\Exception $e) {
            //goes silent
        }      

        Session::flash('success', 'Documento enviado com sucesso!');

        return Response::json(['status' => 'success', 'type' => 'reload']);
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
