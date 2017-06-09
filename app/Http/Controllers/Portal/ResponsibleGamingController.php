<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Traits\GenericResponseTrait;
use App\ListSelfExclusion;
use App\UserRevocation;
use App\UserSession;
use Exception;
use Illuminate\Http\JsonResponse;
use Session, View, Response, Auth, Mail, Validator;
use Illuminate\Http\Request;
use App\User, App\SelfExclusionType, App\Status;

class ResponsibleGamingController extends Controller
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
        $this->middleware('auth', ['except' => 'index']);
        $this->request = $request;
        $this->authUser = Auth::user();
        $this->userSessionId = Session::get('user_session');

        View::share('authUser', $this->authUser, 'request', $request);
    }

    /**
     * Display jogo-responsavel/limites page
     *
     * @return \View
     */
    public function limitsGet()
    {
        return view('portal.responsible_gaming.limits');
    }

    /**
     * Handle jogo-responsavel/limites POST
     *
     * @return JsonResponse
     */
    public function limitsDepositsPost()
    {
        $inputs = $this->request->only(
            'limit_daily_deposit', 'limit-daily_deposit',
            'limit_weekly_deposit', 'limit-weekly_deposit',
            'limit_monthly_deposit', 'limit-monthly_deposit'
        );

        if (!$inputs['limit-daily_deposit']) unset($inputs['limit_daily_deposit']);
        if (!$inputs['limit-weekly_deposit']) unset($inputs['limit_weekly_deposit']);
        if (!$inputs['limit-monthly_deposit']) unset($inputs['limit_monthly_deposit']);

        $inputs = array_map(function ($n) { return str_replace(' ', '', $n); }, $inputs);

        $validator = Validator::make($inputs, User::$rulesForLimits, User::$messagesForLimits);
        if ($validator->fails()) {
            $messages = $validator->messages()->getMessages();
            return $this->respType('error' , $messages);
        }

        if (! $this->authUser->changeLimits($inputs, 'deposits'))
            return $this->respType('error' , ['limites' => 'Ocorreu um erro a alterar os limites, por favor tente novamente.']);

        return $this->respType('success', 'Limites alterados com sucesso!', 'reload');
    }

    /**
     * Handle jogo-responsavel/limites/apostas POST
     *
     * @return JsonResponse
     */
    public function limitsBetsPost()
    {
        $inputs = $this->request->only(
            'limit_daily_bet', 'limit-daily_bet',
            'limit_weekly_bet', 'limit-weekly_bet',
            'limit_monthly_bet', 'limit-monthly_bet'
        );

        if (!$inputs['limit_daily_bet']) unset($inputs['limit_daily_bet']);
        if (!$inputs['limit_weekly_bet']) unset($inputs['limit_weekly_bet']);
        if (!$inputs['limit_monthly_bet']) unset($inputs['limit_monthly_bet']);

        $inputs = array_map(function ($n) { return str_replace(' ', '', $n); }, $inputs);

        $validator = Validator::make($inputs, User::$rulesForLimits, User::$messagesForLimits);
        if ($validator->fails()) {
            $messages = $validator->messages()->getMessages();
            return $this->respType('error' , $messages);
        }

        if (! $this->authUser->changeLimits($inputs, 'bets'))
            return $this->respType('error' , ['limites' => 'Ocorreu um erro a alterar os limites, por favor tente novamente.']);

        return $this->respType('success', 'Limites alterados com sucesso!', 'reload');
    }

    /**
     * Display jogo-responsavel/autoexclusao page
     *
     * @return \View
     */
    public function selfExclusionGet()
    {
        $canSelfExclude = $this->authUser->checkCanSelfExclude();
        
        $selfExclusion = $this->authUser->getSelfExclusion();
        $selfExclusionTypes = SelfExclusionType::query()
            ->where('priority', '<', 10)
            ->orderBy('priority')
            ->lists('name', 'id');
        $statuses = Status::whereIn('id', ['suspended_3_months','suspended_6_months','suspended_1_year'])->lists('name', 'id');
        $revocation = $selfExclusion != null ? $selfExclusion->hasRevocation() : null;

        return view('portal.responsible_gaming.selfexclusion', compact('selfExclusionTypes', 'canSelfExclude', 'statuses', 'selfExclusion', 'revocation'));
    }
    /**
     * Handle jogo-responsavel/autoexclusao POST
     *
     * @return JsonResponse
     */
    public function selfExclusionPost()
    {
        try {
            $canSelfExclude = $this->authUser->checkCanSelfExclude();
            if (!$canSelfExclude)
                return $this->resp('error', 'O utilizador ainda não foi validado, não pode concluir esta acção agora.');

            $inputs = $this->request->only(['rp_dias', 'se_meses', 'motive', 'self_exclusion_type']);

            $selfExclusion = $this->authUser->getSelfExclusion();
            if ($selfExclusion !== null)
                return $this->resp('error', 'Já existe uma autoexclusão activa.');

            if (! $this->authUser->selfExclusionRequest($inputs))
                return $this->resp('error', 'Ocorreu um erro a efetuar o pedido de autoexclusão, por favor tente novamente.');
        } catch (Exception $e) {
            return $this->resp('error', $e->getMessage());
        }

        return $this->respType('success', 'Pedido de autoexclusão efetuado com sucesso!', ['type' => 'reload']);
    }

    /**
     * Cancel Self Exclusion
     *
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function cancelSelfExclusionPost()
    {
        $inputs = $this->request->only(['self_exclusion_id']);
        if (empty($inputs['self_exclusion_id']))
            return $this->respType('error', 'Não foi encontrado o id da Autoexclusão no Pedido!');

        $selfExclusion = $this->authUser->getSelfExclusion();
        if ($selfExclusion === null) {
            return $this->respType('error', 'Não foi encontrado nenhuma Autoexclusão!');
        }
        if ($selfExclusion->id !== $inputs['self_exclusion_id']){
            return $this->respType('error', 'A Autoexclusão não está correcta!');
        }

        $selfExclusionSRIJ = ListSelfExclusion::validateSelfExclusion([
            'document_number' => $this->authUser->profile->document_number
        ]);
        if ($selfExclusionSRIJ !== null && $selfExclusionSRIJ->origin === 'srij') {
            return $this->respType('error', 'O pedido de Revogação tem de ser efetuado no site da SRIJ!');
        }

        if (! $this->authUser->requestRevoke($selfExclusion, $this->userSessionId)){
            return $this->respType('error', 'Occurreu um erro ao registar a sua Revogação!');
        }

        return $this->respType('success', 'Revogação ao seu pedido efectuada com sucesso!', ['type' => 'reload']);
    }

    /**
     * Revoke Self Exclusion
     *
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function revokeSelfExclusionPost()
    {
        $inputs = $this->request->only(['user_revocation_id']);
        if (empty($inputs['user_revocation_id']))
            return $this->respType('error', 'Não foi encontrado o id da Autoexclusão no Pedido!');

        $selfExclusion = $this->authUser->getSelfExclusion();
        if ($selfExclusion === null) {
            return $this->respType('error', 'Não foi encontrado nenhuma Autoexclusão!');
        }
        if (($revocation = $selfExclusion->hasRevocation()) === null){
            return $this->respType('error', 'A Revogação da Autoexclusão não está correcta!');
        }

        if (! $this->authUser->cancelRevoke($revocation, $this->userSessionId)){
            return $this->respType('error', 'Occurreu um erro ao cancelar a sua Revogação!');
        }

        return $this->respType('success', 'Cancelamento do pedido de Revogação efectuado com sucesso!', ['type' => 'reload']);
    }

    public function getLastLogins() {
        $sessions = UserSession::query()
            ->where('user_id', '=', $this->authUser->id)
            ->whereIn('session_type', ['login', 'login_fail', 'timeout', 'logout'])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        return view('portal.responsible_gaming.login_log', compact('sessions'));
    }
}
