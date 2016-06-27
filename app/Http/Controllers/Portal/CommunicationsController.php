<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\UserComplain;
use App\User;
use App\UserSession;
use App\UserSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Input;
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
        $settings = $this->authUser->settings()->first();

        return view('portal.communications.settings', compact('settings'));
    }
    /**
     * Handle comunicacoes definicoes POST
     *
     * @return array Json array
     */

    public function settingsPost()
    {
        if (!UserSetting::updateSettings(Input::get(), Auth::user()->id, Session::get('user_session')))
            return Response::json( [ 'status' => 'error', 'msg' => 'Ocorreu um erro ao alterar as definições.' ] );

        return Response::json(['status' => 'success', 'msg' => 'Definições alteradas com sucesso.']);
    }
    /**
     * Display mensagens page
     *
     * @return \View
     */
  

    public function complaintsGet()
    {
        $complaints = $this->authUser->complaints;

        return view('portal.communications.reclamacoes', compact('complaints'));
    }
    public function complaintsPost()
    {
        $complaints = $this->authUser->complaints;
        try {
            DB::beginTransaction();

            if (!$userSession = UserSession::logSession('user_complaint', 'user_complaint'))
                throw new \Exception('Falha ao gravar na sessão.');
            $reclamacao = $this->request->get('reclamacao');
            $complaint = new UserComplain();

            $complaint->data = Carbon::now()->toDateTimeString();
            $complaint->complaint = $reclamacao;
            $complaint->user_id = Auth::user()->id;
            $complaint->user_session_id = $userSession->id;
            $complaint->solution_time = Carbon::now()->addDays(2);
            if (!$complaint->save())
                throw new \Exception('Falha ao gravar.');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return Response::json( [ 'status' => 'error', 'msg' => 'Ocorreu um erro ao gravar as definições.' ] );
        }

        return Response::json(['status' => 'success', 'msg' => 'Reclamação gravada com sucesso.']);
    }


}
