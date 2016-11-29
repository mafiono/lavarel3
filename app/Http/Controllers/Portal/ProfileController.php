<?php

namespace App\Http\Controllers\Portal;

use App\Enums\DocumentTypes;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\UserDocumentAttachment;
use App\Models\Highlight;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Mail\Message;
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
        $competitions = Highlight::competitions()->get(['highlight_id']);
        $countryList = Country::query()
            ->where('cod_num', '>', 0)
            ->orderby('name')->lists('name','cod_alf2')->all();
        return view('portal.profile.personal_info',compact('countryList','competitions'));
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
            if (! $doc = $this->authUser->addDocument($file, DocumentTypes::$Address))
                return Response::json(['status' => 'error', 'msg' => ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']]);
        }

        Session::flash('success', 'Perfil alterado com sucesso!');

        return back();
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
     * @return RedirectResponse
     */
    public function identityAuthenticationPost()
    {
        $ajax = $this->request->get('ajax', false);

        if (! $this->request->hasFile('upload')) {
            return $this->resp($ajax, 'error', 'Por favor escolha um documento a enviar.');
        }

        if (! $this->request->file('upload')->isValid()) {
            return $this->resp($ajax, 'error', 'Ocorreu um erro a enviar o documento, por favor tente novamente.');
        }

        $file = $this->request->file('upload');

        if ($file->getClientSize() >= $file->getMaxFilesize() || $file->getClientSize() > 5000000) {
            return $this->resp($ajax, 'error', 'O tamanho máximo aceite é de 5mb.');
        }

        if (! $doc = $this->authUser->addDocument($file, DocumentTypes::$Identity)) {
            return $this->resp($ajax, 'error', 'Ocorreu um erro a enviar o documento, por favor tente novamente.');
        }

       /*
        * Enviar email com o anexo
        */
        try {
            Mail::send('portal.profile.emails.authentication', ['user' => $this->authUser], function (Message $m) use ($file) {
                $m->to(env('MAIL_USERNAME'), env('MAIL_NAME'))->subject('Autenticação de Identidade - Novo Documento');
                $m->cc(env('TEST_MAIL'), env('TEST_MAIL_NAME'));
                $m->attach($file->getRealPath());
            });
        } catch (\Exception $e) {
            //goes silent
        }
        return $this->resp($ajax, 'success', 'Documento enviado com sucesso!');
    }

    public function addressAuthenticationPost()
    {
        $ajax = $this->request->get('ajax', false);

        if (! $this->request->hasFile('upload2')) {
            return $this->resp($ajax, 'error', 'Por favor escolha um documento a enviar.');
        }

        if (! $this->request->file('upload2')->isValid()) {
            return $this->resp($ajax, 'error', 'Ocorreu um erro a enviar o documento, por favor tente novamente.');
        }

        $file = $this->request->file('upload2');

        if ($file->getClientSize() >= $file->getMaxFilesize() || $file->getClientSize() > 5000000) {
            return $this->resp($ajax, 'error', 'O tamanho máximo aceite é de 5mb.');
        }

        if (! $doc = $this->authUser->addDocument($file, DocumentTypes::$Address)) {
            return $this->resp($ajax, 'error', 'Ocorreu um erro a enviar o documento, por favor tente novamente.');
        }

        /*
         * Enviar email com o anexo
         */
        try {
            Mail::send('portal.profile.emails.authentication', ['user' => $this->authUser], function (Message $m) use ($file) {
                $m->to(env('MAIL_USERNAME'), env('MAIL_NAME'))->subject('Autenticação de Morada - Novo Documento');
                $m->cc(env('TEST_MAIL'), env('TEST_MAIL_NAME'));
                $m->attach($file->getRealPath());
            });
        } catch (\Exception $e) {
            //goes silent
        }

        return $this->resp($ajax, 'success', 'Documento enviado com sucesso!');
    }
    public function downloadAttachment()
    {
        $id = $this->request->get('id');
        if (! $id || $id <= 0)
            return Response::json(['status' => 'error', 'msg' => 'Id inválido']);

        $docAtt = UserDocumentAttachment::query()
            ->where('user_id', '=', $this->authUser->id)
            ->where('user_document_id', '=', $id)
            ->first();
        if ($docAtt == null)
            return Response::json(['status' => 'error', 'msg' => 'Id inválido']);

        $doc = $this->authUser->findDocById($id);
        if ($doc == null)
            return Response::json(['status' => 'error', 'msg' => 'Id inválido']);

        $response = Response::make($docAtt->data, 200);
        $response->header('Content-Type', 'application/octet-stream');
        $response->header('Content-Disposition', 'attachment; filename='.$doc->description);
        return $response;
    }

    /**
     * Display user profile/password page
     *
     * @return \View
     */
    public function codesGet()
    {
        return view('portal.profile.codes');
    }
    /**
     * Handle perfil password POST
     *
     * @return JsonResponse
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
     * Handle perfil codigo pin POST
     *
     * @return JsonResponse
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
     * @return JsonResponse
     */
    public function getBalance()
    {
        return Response::json(['balance' => $this->authUser->balance->balance_available]);
    }

    private function resp($ajax, $type, $msg)
    {
        if ($ajax) {
            return Response::json([$type => $msg], $type === 'success' ? 200 : 400);
        } else {
            Session::flash($type, $msg);
        }
        return back();
    }
}
