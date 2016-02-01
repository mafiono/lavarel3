<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
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
        $this->userSessionId = Session::get('userSessionId');

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
     * @return array Json array
     */
    public function limitsPost()
    {
        $inputs = $this->request->only('limit_betting_daily', 'limit_betting_weekly', 'limit_betting_monthly');

        $validator = Validator::make($inputs, User::$rulesForLimits, User::$messagesForRegister);
        if ($validator->fails()) {
            $messages = User::buildValidationMessageArray($validator);
            return Response::json( [ 'status' => 'error', 'msg' => $messages ] );
        }

        if (! $this->authUser->changeLimits($inputs, $this->userSessionId))
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
        $selfExclusionTypes = SelfExclusionType::lists('name', 'id');
        $statuses = Status::whereIn('id', ['suspended_3_months','suspended_6_months','suspended_1_year'])->lists('name', 'id');

        return view('portal.responsible_gaming.selfexclusion', compact('selfExclusionTypes', 'statuses'));
    }      

    /**
     * Handle jogo-responsavel/autoexclusao POST
     *
     * @return array Json array
     */
    public function selfExclusionPost()
    {
        $inputs = $this->request->only('status', 'self_exclusion_type');

        if (! $this->authUser->selfExclusionRequest($inputs, $this->userSessionId))
            return Response::json(['status' => 'error', 'msg' => ['password' => 'Ocorreu um erro a efetuar o pedido de auto-exclusão, por favor tente novamente.']]);

        Session::flash('success', 'Pedido de auto-exclusão efetuado com sucesso!');

        return Response::json(['status' => 'success', 'type' => 'reload']);
    }            
               
}
