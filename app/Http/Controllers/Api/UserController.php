<?php
namespace App\Http\Controllers\Api;

use App\Enums\DocumentTypes;
use App\Http\Controllers\Controller;
use App\User, App\UserDocument, App\UserSetting, App\UserSession;
use Hash, Input;
use Session, View, Response, Auth, Mail, Validator;
use Illuminate\Http\Request;

class UserController extends Controller {
    protected $authUser;
    protected $request;
    protected $userSessionId;

    public function __construct(Request $request) {
        $this->request = $request;
        $this->authUser = Auth::user();
    }

    /**
     * Get the user
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthenticatedUser()
    {
        $user = $this->authUser->profile->toArray();
        return response()->json(compact('user'));
    }
    /**
     * Get the user status
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserStatus()
    {
        $status = $this->authUser->status->toArray();
        return response()->json(compact('status'));
    }

    /**
     * Post the change of user
     * @return \Illuminate\Http\JsonResponse
     */
    public function postProfile()
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

        return Response::json(['status' => 'success', 'type' => 'reload', 'msg' => 'Perfil alterado com sucesso!']);
    }

    /**
     * Change Password
     * @return \Illuminate\Http\JsonResponse
     */
    public function postResetPassword()
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

        return Response::json(['status' => 'success', 'type' => 'reload', 'msg' => 'Password alterada com sucesso!']);
    }

    /**
     * Change Pin
     * @return \Illuminate\Http\JsonResponse
     */
    public function postResetPin()
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

        return Response::json(['status' => 'success', 'type' => 'reload', 'msg' => 'Código Pin alterado com sucesso!']);
    }
    
    public function postUploadIdentity() 
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
        return Response::json(['status' => 'success', 'type' => 'reload', 'msg' => 'Documento enviado com sucesso!']);
    }
    public function postUploadAddress()
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

        return Response::json(['status' => 'success', 'type' => 'reload', 'msg' => 'Documento enviado com sucesso!']);
    }
    
    public function getUploadedDocs()
    {
        $type = $this->request->only('type');
        $docs = $this->authUser->findDocsByType($type);

        return Response::json(compact('docs'));
    }
    
    public function getUserBalance()
    {
        $balance = $this->authUser->balance;
        
        return Response::json(compact('balance'));
    }
    
    public function getUserSettings() 
    {
        $settings = $this->authUser->settings()->first();
        // TODO: check this later, hack for now...
        return Response::json(compact('settings'), 200, [], JSON_NUMERIC_CHECK);
    }
    
    public function postUserSettings() 
    {
        $inputs = $this->request->all();
        $userSessionId = UserSession::getSessionId();
        if (!UserSetting::updateSettings($inputs, $this->authUser->id, $userSessionId))
            return Response::json( [ 'status' => 'error', 'msg' => 'Ocorreu um erro ao alterar as definições.' ] );

        return Response::json(['status' => 'success', 'msg' => 'Definições alteradas com sucesso.']);
    }
    
    public function getUserNetwork() 
    {
        $network = $this->authUser->friendInvites()->get();
        return Response::json(compact('network'));
    }

    /* Promotions */
    private function formatBonusValue($bonuses) {
        foreach ($bonuses as $bonus) {
            $bonus->value = floor($bonus->value);
            if (($bonus->bonus_type_id === 'first_deposit') || ($bonus->bonus_type_id === 'deposits' && $bonus->value_type === 'percentage'))
                $bonus->value .= '%';
        }
    }
    public function getBonus($tipo = null) {
        if ($tipo == null || $tipo == 'desportos') {
            $availableBonuses = $this->authUser->availableBonuses();
        } else if ($tipo == 'casino') {
            $availableBonuses = [];
        } else {
            // rede de amigos
            $availableBonuses = [];
        }
        return Response::json(compact('availableBonuses'));
    }
    public function getActiveBonuses() {
        $activeBonuses = $this->authUser->activeBonuses()
            ->join('bonus_types', 'bonus.bonus_type_id', '=', 'bonus_types.id')
            ->get([
                'bonus.id as id', 'title', 'value_type', 'value', 'bonus_type_id', 'name'
            ]);
        $this->formatBonusValue($activeBonuses);
        return Response::json(compact('activeBonuses'));
    }
    public function getConsumedBonuses() {
        $consumedBonuses = $this->authUser->consumedBonuses()
            ->join('bonus_types', 'bonus.bonus_type_id', '=', 'bonus_types.id')
            ->get([
                'bonus.id as id', 'title', 'value_type', 'value', 'bonus_type_id', 'name', 'bonus.updated_at as date'
            ]);
        $this->formatBonusValue($consumedBonuses);
        return Response::json(compact('consumedBonuses'));
    }

    public function postRedeemBonus() {
        $bonus_id = $this->request->get('id');
        $success = $this->authUser->redeemBonus($bonus_id);
        if ($success)
            return Response::json(['status' => 'success', 'msg' => 'Bonus resgatado com sucesso.']);
        else
            return Response::json(['status' => 'error', 'msg' => 'Não é possível resgatar o bonus.']);
    }

    public function postCancelBonus() {
        $bonus_id = $this->request->get('id');
        $success = $this->authUser->cancelBonus($bonus_id);
        if ($success)
            return Response::json(['status' => 'success', 'msg' => 'Bonus cancelado.']);
        else
            return Response::json(['status' => 'error', 'msg' => 'Não é possível cancelar o bonus.']);
    }
}