<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Session, View, Response, Auth, Mail, Validator;
use Illuminate\Http\Request;
use App\JogadorConta, App\JogadorDefinicoes;

class CommunicationsController extends Controller
{
    protected $authUser;

    protected $request;

    protected $userSessionId;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->request = $request;
        $this->authUser = Auth::user();
        $this->userSessionId = Session::get('user_session');

        View::share('authUser', $this->authUser, 'request', $request);        
    }

    /**
     * Display user comunicacao/definicoes page
     *
     * @return \View
     */
    public function settingsGet()
    {
        $settings = $this->authUser->settings()->lists('value', 'settings_type_id');

        return view('portal.communications.settings', compact('settings'));
    }
    /**
     * Handle comunicacoes definicoes POST
     *
     * @return array Json array
     */
    public function settingsPost()
    {
        $inputs = $this->request->only(['type', 'value']);

        if ($inputs['value'] == true)
            $inputs['value'] = 1;
        else
            $inputs['value'] = 0;

        if (!isset($inputs['type']) || !isset($inputs['value']))
            return Response::json( [ 'status' => 'error', 'msg' => 'Ocorreu um erro a alterar a definição.' ] );

        $inputs['user_id'] = $this->authUser->id;
        if (!$this->authUser->updateSettings($inputs, $this->userSessionId))
            return Response::json( [ 'status' => 'error', 'msg' => 'Ocorreu um erro a alterar a definição.' ] );

        return Response::json(['status' => 'success', 'msg' => 'Definição alterada com sucesso.']);
    }
    /**
     * Display mensagens page
     *
     * @return \View
     */
    public function messagesGet()
    {
        return view('portal.communications.messages');
    }
}
