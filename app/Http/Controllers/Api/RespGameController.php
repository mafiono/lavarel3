<?php
namespace App\Http\Controllers\Api;

use App\Enums\DocumentTypes;
use App\Http\Controllers\Controller;
use App\User, App\UserDocument, App\UserSetting, App\UserSession;
use App\UserBonus;
use App\UserLimit;
use Hash, Input;
use Session, View, Response, Auth, Mail, Validator;
use Illuminate\Http\Request;

class RespGameController extends Controller {
    protected $authUser;
    protected $request;
    protected $userSessionId;

    public function __construct(Request $request) {
        $this->request = $request;
        $this->authUser = Auth::user();
    }

    public function getLimitsDeposit()
    {
        $limits = [
            'limit_daily' => ['curr' => UserLimit::GetCurrLimitFor('limit_deposit_daily'),
                'last' => UserLimit::GetLastLimitFor('limit_deposit_daily')],
            'limit_weekly' => ['curr' => UserLimit::GetCurrLimitFor('limit_deposit_weekly'),
                'last' => UserLimit::GetLastLimitFor('limit_deposit_weekly')],
            'limit_monthly' => ['curr' => UserLimit::GetCurrLimitFor('limit_deposit_monthly'),
                'last' => UserLimit::GetLastLimitFor('limit_deposit_monthly')],
        ];
        return Response::json(compact('limits'));
    }

    public function getLimitsBets()
    {
        $limits = [
            'limit_daily' => ['curr' => UserLimit::GetCurrLimitFor('limit_betting_daily'),
                'last' => UserLimit::GetLastLimitFor('limit_betting_daily')],
            'limit_weekly' => ['curr' => UserLimit::GetCurrLimitFor('limit_betting_weekly'),
                'last' => UserLimit::GetLastLimitFor('limit_betting_weekly')],
            'limit_monthly' => ['curr' => UserLimit::GetCurrLimitFor('limit_betting_monthly'),
                'last' => UserLimit::GetLastLimitFor('limit_betting_monthly')],
        ];
        return Response::json(compact('limits'));
    }

    public function postLimitsDeposits()
    {
        $inputs = $this->request->only(
            'limit_daily',
            'limit_weekly',
            'limit_monthly'
        );
        if ($inputs['limit_daily'] === null) unset($inputs['limit_daily']);
        if ($inputs['limit_weekly'] === null) unset($inputs['limit_weekly']);
        if ($inputs['limit_monthly'] === null) unset($inputs['limit_monthly']);

        $validator = Validator::make($inputs, User::$rulesForLimits, User::$messagesForLimits);
        if ($validator->fails()) {
            $messages = $validator->messages()->getMessages();
            return Response::json(['status' => 'error', 'msg' => $messages]);
        }

        if (!$this->authUser->changeLimits($inputs, 'deposits'))
            return Response::json(['status' => 'error', 'msg' => ['password' => 'Ocorreu um erro a alterar os limites, por favor tente novamente.']]);

        return Response::json(['status' => 'success', 'type' => 'reload', 'msg' => 'Limites alterados com sucesso!']);
    }

    public function postLimitsBets()
    {
        $inputs = $this->request->only(
            'limit_daily',
            'limit_weekly',
            'limit_monthly'
        );
        if ($inputs['limit_daily'] === null) unset($inputs['limit_daily']);
        if ($inputs['limit_weekly'] === null) unset($inputs['limit_weekly']);
        if ($inputs['limit_monthly'] === null) unset($inputs['limit_monthly']);

        $validator = Validator::make($inputs, User::$rulesForLimits, User::$messagesForLimits);
        if ($validator->fails()) {
            $messages = $validator->messages()->getMessages();
            return Response::json(['status' => 'error', 'msg' => $messages]);
        }

        if (!$this->authUser->changeLimits($inputs, 'bets'))
            return Response::json(['status' => 'error', 'msg' => ['password' => 'Ocorreu um erro a alterar os limites, por favor tente novamente.']]);

        return Response::json(['status' => 'success', 'type' => 'reload', 'msg' => 'Limites alterados com sucesso!']);
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
            ->orderBy('priority')
            ->lists('name', 'id');
        $statuses = Status::whereIn('id', ['suspended_3_months', 'suspended_6_months', 'suspended_1_year'])->lists('name', 'id');
        $revocation = $selfExclusion != null ? $selfExclusion->hasRevocation() : null;

        return view('portal.responsible_gaming.selfexclusion', compact('selfExclusionTypes', 'canSelfExclude', 'statuses', 'selfExclusion', 'revocation'));
    }
    /**
     * Handle jogo-responsavel/autoexclusao POST
     *
     * @return array Json array
     */
    public function selfExclusionPost()
    {
        $canSelfExclude = $this->authUser->checkCanSelfExclude();
        if (!$canSelfExclude)
            return Response::json(['status' => 'error', 'msg' => ['geral' => 'O utilizador ainda não foi validado, não pode concluir esta acção agora.']]);

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