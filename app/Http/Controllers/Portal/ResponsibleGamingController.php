<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\UserRevocation;
use Session, View, Response, Auth, Mail, Validator;
use Illuminate\Http\Request;
use App\User, App\SelfExclusionType, App\Status;

class ResponsibleGamingController extends Controller
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
        return view('portal.responsible_gaming.limits_deposit');
    }

    /**
     * Handle jogo-responsavel/limites POST
     *
     * @return array Json array
     */
    public function limitsPost()
    {
        $inputs = $this->request->only(
            'limit_daily', 'limit-daily',
            'limit_weekly', 'limit-weekly',
            'limit_monthly', 'limit-monthly'
        );
        if (!$inputs['limit-daily']) unset($inputs['limit_daily']);
        if (!$inputs['limit-weekly']) unset($inputs['limit_weekly']);
        if (!$inputs['limit-monthly']) unset($inputs['limit_monthly']);

        $validator = Validator::make($inputs, User::$rulesForLimits, User::$messagesForLimits);
        if ($validator->fails()) {
            $messages = $validator->messages()->getMessages();
            return Response::json( [ 'status' => 'error', 'msg' => $messages ] );
        }

        if (! $this->authUser->changeLimits($inputs, 'deposits'))
            return Response::json(['status' => 'error', 'msg' => ['password' => 'Ocorreu um erro a alterar os limites, por favor tente novamente.']]);

        Session::flash('success', 'Limites alterados com sucesso!');

        return Response::json(['status' => 'success', 'type' => 'reload']);
    }

    /**
     * Display jogo-responsavel/limites/apostas page
     *
     * @return \View
     */
    public function limitsBetsGet()
    {
        return view('portal.responsible_gaming.limits_bets');
    }

    /**
     * Handle jogo-responsavel/limites/apostas POST
     *
     * @return array Json array
     */
    public function limitsBetsPost()
    {
        $inputs = $this->request->only(
            'limit_daily', 'limit-daily',
            'limit_weekly', 'limit-weekly',
            'limit_monthly', 'limit-monthly'
        );
        if (!$inputs['limit-daily']) unset($inputs['limit_daily']);
        if (!$inputs['limit-weekly']) unset($inputs['limit_weekly']);
        if (!$inputs['limit-monthly']) unset($inputs['limit_monthly']);

        $validator = Validator::make($inputs, User::$rulesForLimits, User::$messagesForLimits);
        if ($validator->fails()) {
            $messages = $validator->messages()->getMessages();
            return Response::json( [ 'status' => 'error', 'msg' => $messages ] );
        }

        if (! $this->authUser->changeLimits($inputs, 'bets'))
            return Response::json(['status' => 'error', 'msg' => ['password' => 'Ocorreu um erro a alterar os limites, por favor tente novamente.']]);

        Session::flash('success', 'Limites alterados com sucesso!');

        return Response::json(['status' => 'success', 'type' => 'reload']);
    }

    /**
     * Display jogo-responsavel/autoexclusao page
     *
     * @return \View
     */
    public function selfExclusionGet()
    {
        $selfExclusion = $this->authUser->getSelfExclusion();
        $selfExclusionTypes = SelfExclusionType::query()
            ->orderBy('priority')
            ->lists('name', 'id');
        $statuses = Status::whereIn('id', ['suspended_3_months','suspended_6_months','suspended_1_year'])->lists('name', 'id');
        $revocation = $selfExclusion != null ? $selfExclusion->hasRevocation() : null;

        return view('portal.responsible_gaming.selfexclusion', compact('selfExclusionTypes', 'statuses', 'selfExclusion', 'revocation'));
    }
    /**
     * Handle jogo-responsavel/autoexclusao POST
     *
     * @return array Json array
     */
    public function selfExclusionPost()
    {
        $inputs = $this->request->only('dias', 'motive', 'self_exclusion_type');

        $selfExclusion = $this->authUser->getSelfExclusion();
        if ($selfExclusion != null)
            return Response::json(['status' => 'error', 'msg' => ['geral' => 'Ocorreu um erro a efetuar o pedido de auto-exclusão, por favor tente novamente.']]);

        if (! $this->authUser->selfExclusionRequest($inputs))
            return Response::json(['status' => 'error', 'msg' => ['geral' => 'Ocorreu um erro a efetuar o pedido de auto-exclusão, por favor tente novamente.']]);

        Session::flash('success', 'Pedido de auto-exclusão efetuado com sucesso!');

        return Response::json(['status' => 'success', 'type' => 'reload']);
    }
    public function cancelSelfExclusionPost()
    {
        $inputs = $this->request->only('self_exclusion_id');
        if (empty($inputs['self_exclusion_id']))
            return Response::redirectTo('jogo-responsavel/autoexclusao')
                ->with('error', 'Não foi encontrado o id da Auto-Exclusão no Pedido!');

        $selfExclusion = $this->authUser->getSelfExclusion();
        if ($selfExclusion === null) {
            return Response::redirectTo('jogo-responsavel/autoexclusao')
                ->with('error', 'Não foi encontrado nenhuma Auto-Exclusão!');
        }
        if ($selfExclusion->id != $inputs['self_exclusion_id']){
            return Response::redirectTo('jogo-responsavel/autoexclusao')
                ->with('error', 'A Auto-Exclusão não está correcta!');
        }

        if (! $this->authUser->requestRevoke($selfExclusion, $this->userSessionId)){
            return Response::redirectTo('jogo-responsavel/autoexclusao')
                ->with('error', 'Occurreu um erro ao registar a sua Revogação!');
        }

        return Response::redirectTo('jogo-responsavel/autoexclusao')
            ->with('success', 'Revogação ao seu pedido efectuada com sucesso!');
    }
    public function revokeSelfExclusionPost()
    {
        $inputs = $this->request->only('user_revocation_id');
        if (empty($inputs['user_revocation_id']))
            return Response::redirectTo('jogo-responsavel/autoexclusao')
                ->with('error', 'Não foi encontrado o id da Auto-Exclusão no Pedido!');

        $selfExclusion = $this->authUser->getSelfExclusion();
        if ($selfExclusion === null) {
            return Response::redirectTo('jogo-responsavel/autoexclusao')
                ->with('error', 'Não foi encontrado nenhuma Auto-Exclusão!');
        }
        if (($revocation = $selfExclusion->hasRevocation()) === null){
            return Response::redirectTo('jogo-responsavel/autoexclusao')
                ->with('error', 'A Revogação da Auto-Exclusão não está correcta!');
        }

        if (! $this->authUser->cancelRevoke($revocation, $this->userSessionId)){
            return Response::redirectTo('jogo-responsavel/autoexclusao')
                ->with('error', 'Occurreu um erro ao cancelar a sua Revogação!');
        }

        return Response::redirectTo('jogo-responsavel/autoexclusao')
            ->with('success', 'Cancelamento do pedido de Revogação efectuado com sucesso!');
    }

}
