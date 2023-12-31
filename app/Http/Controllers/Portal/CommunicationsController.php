<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Traits\GenericResponseTrait;
use App\Lib\Mail\SendMail;
use App\Models\UserComplain;
use App\User;
use App\UserSession;
use App\UserSetting;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Input;
use Log;
use Session, View, Response, Auth, Mail, Validator;
use Illuminate\Http\Request;
use App\JogadorConta, App\JogadorDefinicoes;

class CommunicationsController extends Controller
{
    use GenericResponseTrait;

    protected $authUser;

    protected $request;

    protected $userSessionId;

    /**
     * Constructor
     *
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
        /** @var UserSetting $settings */
        $settings = $this->authUser->settings()->first();

        if($settings!==null && !$settings->consent){
            /* Create User Session */
            $userSession = $this->authUser->logUserSession('give.consent', 'Consent Given');
            $settings->giveConsent($userSession->id);
        }

        return view('portal.communications.settings', compact('settings'));
    }
    /**
     * Handle comunicacoes definicoes POST
     *
     * @return JsonResponse
     */

    public function settingsPost()
    {
        if (!UserSetting::updateSettings(Input::get(), Auth::user()->id, Session::get('user_session')))
            return $this->respType('error', 'Ocorreu um erro ao alterar as definições.');

        return $this->respType('empty', 'Alterado com sucesso.');
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
        $reclamacao = $this->request->get('reclamacao');
        if (empty($reclamacao) || strlen($reclamacao) < 10)
            return $this->respType('error', 'Por favor explique o mais detalhado possível o problema/reclamação.');

        try {
            DB::beginTransaction();

            if (!$userSession = $this->authUser->logUserSession('user_complaint', 'user_complaint'))
                throw new Exception('Falha ao gravar na sessão.');

            $erro = 0;
            $complaint = new UserComplain();
            $complaint->data = Carbon::now()->toDateTimeString();
            $complaint->complaint = $reclamacao;
            $complaint->user_id = $this->authUser->id;
            $complaint->user_session_id = $userSession->id;
            $complaint->solution_time = Carbon::now()->addDays(2);
            $complaint->result = "Pending";

            if($last = $this->authUser->complaints->last()) {
                if (strtolower($last->complaint) === strtolower($complaint->complaint)) {
                    throw new Exception('Reclamação duplicada.');
                }
            }

            if (!$complaint->save())
                throw new Exception('Falha ao gravar.');

            $mail = new SendMail(SendMail::$TYPE_20_COMPLAIN);
            $mail->prepareMail($this->authUser, [
                'title' => 'Reclamação',
                'motive' => $reclamacao,
            ], $userSession->id);
            $mail->Send(false);

            DB::commit();
        } catch (Exception $e) {
            Log::error('Error inserting Complain: '. $e->getMessage());
            DB::rollBack();
            return $this->respType('error', $e->getMessage());
        }
        return $this->respType('success', 'Reclamação enviada com sucesso!');
    }


}
