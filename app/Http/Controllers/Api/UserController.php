<?php
namespace App\Http\Controllers\Api;

use App\Bonus;
use App\Enums\DocumentTypes;
use App\Enums\ValidFileTypes;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\User, App\UserDocument, App\UserSetting, App\UserSession;
use App\UserBankAccount;
use App\UserBet;
use App\UserBonus;
use App\UserTransaction;
use Carbon\Carbon;
use DB;
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



    public function getMessages()
    {
        $id = $this->authUser->id;

        $messages = Message::where('user_id', '=', $id)->get();

        return view('portal.communications.messages', compact('messages'));
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

        if (!ValidFileTypes::isValid($file->getMimeType()))
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Apenas são aceites imagens ou documentos no formato PDF ou WORD.']]);

        if ($file->getClientSize() >= $file->getMaxFilesize() || $file->getClientSize() > 5000000)
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'O tamanho máximo aceite é de 5mb.']]);

        if (! $doc = $this->authUser->addDocument($file, DocumentTypes::$Identity))
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']]);

       /*
        * Enviar email com o anexo
        */
        try {
            Mail::send('portal.profile.emails.authentication', ['user' => $this->authUser], function ($m) use ($doc) {
                $m->to(env('MAIL_USERNAME'), env('MAIL_NAME'))->subject('Autenticação de Identidade - Novo Documento');
                $m->cc(env('TEST_MAIL'), env('TEST_MAIL_NAME'));
                $m->attach($doc->getFullPath());
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

        if (!ValidFileTypes::isValid($file->getMimeType()))
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Apenas são aceites imagens ou documentos no formato PDF ou WORD.']]);

        if ($file->getClientSize() >= $file->getMaxFilesize() || $file->getClientSize() > 5000000)
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'O tamanho máximo aceite é de 5mb.']]);

        if (! $doc = $this->authUser->addDocument($file, DocumentTypes::$Address))
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']]);

        /*
         * Enviar email com o anexo
         */
        try {
            Mail::send('portal.profile.emails.authentication', ['user' => $this->authUser], function ($m) use ($doc) {
                $m->to(env('MAIL_USERNAME'), env('MAIL_NAME'))->subject('Autenticação de Morada - Novo Documento');
                $m->cc(env('TEST_MAIL'), env('TEST_MAIL_NAME'));
                $m->attach($doc->getFullPath());
            });
        } catch (\Exception $e) {
            //goes silent
        }

        return Response::json(['status' => 'success', 'type' => 'reload', 'msg' => 'Documento enviado com sucesso!']);
    }

    public function postUploadIban(Request $request)
    {
        $inputs = $request->only('bank', 'iban');
        $validator = Validator::make($inputs, UserBankAccount::$rulesForCreateAccount);
        if ($validator->fails()) {
            $messages = $validator->messages()->getMessages();
            return Response::json(['status' => 'error', 'msg' => $messages]);
        }
        if (! $this->request->hasFile('upload'))
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Por favor escolha um documento a enviar.']]);

        if (! $this->request->file('upload')->isValid())
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']]);

        $file = $this->request->file('upload');

        if (!ValidFileTypes::isValid($file->getMimeType()))
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Apenas são aceites imagens ou documentos no formato PDF ou WORD.']]);

        if ($file->getClientSize() >= $file->getMaxFilesize() || $file->getClientSize() > 5000000)
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'O tamanho máximo aceite é de 5mb.']]);

        if (! $doc = $this->authUser->addDocument($file, DocumentTypes::$Bank))
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Ocorreu um erro a enviar o documento, por favor tente novamente.']]);

        if (! $this->authUser->createBankAndIban($inputs, $doc))
            return Response::json(['status' => 'error', 'msg' => ['upload' => 'Ocorreu um erro ao gravar, por favor tente novamente.']]);
        /*
         * Enviar email com o anexo
         */
        try {
            Mail::send('portal.profile.emails.authentication', ['user' => $this->authUser], function (Message $m) use ($file) {
                $m->to(env('MAIL_USERNAME'), env('MAIL_NAME'))->subject('Autenticação de Iban - Novo Documento');
                $m->cc(env('TEST_MAIL'), env('TEST_MAIL_NAME'));
                $m->attach($file->getRealPath());
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

    public function getUserComplaints()
    {
        $complaints = $this->authUser->user_complaints->orderBy('created_at', 'desc');
        // TODO: check this later, hack for now...
        return Response::json(compact('complaints'));
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
    public function getBonus($tipo = null) {
        if ($tipo == null || $tipo == 'desportos') {
            $availableBonuses = Bonus::getAvailableBonuses($this->authUser, 'sports');
        } else if ($tipo == 'casino') {
            $availableBonuses = Bonus::getAvailableBonuses($this->authUser, 'casino');
        } else {
            // rede de amigos
            $availableBonuses = [];
        }
        return Response::json(compact('availableBonuses'));
    }
    public function getActiveBonuses() {
        $activeBonuses = UserBonus::activeBonuses($this->authUser->id)
            ->join('bonus', 'user_bonus.bonus_id', '=', 'bonus.id')
            ->join('bonus_types', 'bonus.bonus_type_id', '=', 'bonus_types.id')
            ->get([
                'user_bonus.id as id', 'title', 'value_type', 'value', 'bonus_type_id', 'name'
            ]);
        return Response::json(compact('activeBonuses'));
    }
    public function getConsumedBonuses() {
        $consumedBonuses = UserBonus::consumedBonuses($this->authUser->id)
            ->join('bonus', 'user_bonus.bonus_id', '=', 'bonus.id')
            ->join('bonus_types', 'bonus.bonus_type_id', '=', 'bonus_types.id')
            ->get([
                'user_bonus.id as id', 'title', 'value_type', 'value', 'bonus_type_id', 'name', 'user_bonus.updated_at as date'
            ]);
        return Response::json(compact('consumedBonuses'));
    }

    public function postRedeemBonus() {
        if ($this->authUser->isSelfExcluded())
            return Response::json(['status' => 'error', 'msg' => 'Utilizadores auto-excluidos não podem resgatar bónus.']);
        $bonus_id = $this->request->get('id');
        $success = UserBonus::redeemBonus($this->authUser, $bonus_id);
        if ($success)
            return Response::json(['status' => 'success', 'msg' => 'Bonus resgatado com sucesso.']);
        else
            return Response::json(['status' => 'error', 'msg' => 'Não é possível resgatar o bonus.']);
    }

    public function postCancelBonus() {
        if ($this->authUser->isSelfExcluded())
            return Response::json(['status' => 'error', 'msg' => 'Utilizadores auto-excluidos não podem cancelar bónus.']);
        $bonus_id = $this->request->get('id');
        $success = UserBonus::cancelBonus($this->authUser, $bonus_id);
        if ($success)
            return Response::json(['status' => 'success', 'msg' => 'Bonus cancelado.']);
        else
            return Response::json(['status' => 'error', 'msg' => 'Não é possível cancelar o bonus.']);
    }


    public function postHistory(Request $request) {
        $props = $request->all();

        $trans = UserTransaction::where('user_id', $this->authUser->id)
            ->where('date', '>=', Carbon::createFromFormat('d/m/y H', $props['date_begin'] . ' 0'))
            ->where('date', '<', Carbon::createFromFormat('d/m/y H', $props['date_end'] . ' 24'))
            ->where('status_id', '=', 'processed')
            ->select(DB::raw('`date`, `origin` as `type`, `description`, ' .
                'CONVERT(IFNULL(`debit`, 0) - IFNULL(`credit`, 0), DECIMAL(15,2)) as `value`, ' .
                'CONVERT(0, DECIMAL(15,2)) as `tax`'));
        $bets = UserBet::where('user_id', $this->authUser->id)
            ->where('created_at', '>=', Carbon::createFromFormat('d/m/y H', $props['date_begin'] . ' 0'))
            ->where('created_at', '<', Carbon::createFromFormat('d/m/y H', $props['date_end'] . ' 24'))
            ->select(DB::raw('`created_at` as `date`, ' .
                'CASE `api_bet_type` ' .
                'WHEN \'nyx-casino\' THEN \'Casino\' ' .
                'WHEN \'betconstruct\' THEN \'Desporto\' ' .
                'ELSE NULL END as `type`, ' .
                '`api_bet_id` as `description`, ' .
                'CONVERT(IFNULL(`result_amount`, 0) - IFNULL(`amount`, 0), DECIMAL(15,2)) as `value`,' .
                'CONVERT(IFNULL(`result_tax`, 0), DECIMAL(15,2)) as `tax`'));

        $ignoreTrans = false;
        if (($props['deposits_filter']) && ($props['withdraws_filter'])) {

        } else if (($props['deposits_filter'])) {
            $trans = $trans->where('debit', '>', 0);
        } else if (($props['withdraws_filter'])) {
            $trans = $trans->where('credit', '>', 0);
        } else {
            $ignoreTrans = true;
        }
        $ignoreBets = false;
        if (($props['casino_bets_filter']) && ($props['sports_bets_filter'])) {

        } else if (($props['casino_bets_filter'])) {
            $bets = $bets->where('api_bet_type', '=', 'nyx-casino');
        } else if (($props['sports_bets_filter'])) {
            $bets = $bets->where('api_bet_type', '=', 'betconstruct');
        } else {
            $ignoreBets = true;
        }

        if ($ignoreTrans && $ignoreBets) {
            return Response::json([]);
            // $result = $trans->union($bets);
        } else if ($ignoreTrans) {
            $result = $bets;
        } else if ($ignoreBets) {
            $result = $trans;
        } else {
            $result = $trans->union($bets);
        }
        $result = $result
            ->orderBy('date', 'DESC');

        $list = $result->get();
        return Response::json($list ?: null);
    }

}