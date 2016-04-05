<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Session, View, Response, Auth, Mail, Validator;
use Illuminate\Http\Request;
use App\Bonus;

class PromotionsController extends Controller
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

    protected function formatBonusValue($bonuses) {
        foreach ($bonuses as $bonus) {
            $bonus->value = floor($bonus->value);
            if (($bonus->bonus_type_id === 'first_deposit') || ($bonus->bonus_type_id === 'deposits' && $bonus->value_type === 'percentage'))
                $bonus->value .= '%';
        }
    }

    /**
     * Display index page
     *
     * @return \View
     */
    public function index($tipo = null) {
        if ($tipo == null || $tipo == 'desportos') {
            $availableBonuses = $this->authUser->availableBonuses();
        } else if ($tipo == 'casino') {
            $availableBonuses = [];
        } else {
            // rede de amigos
            $availableBonuses = [];
        }
        return view('portal.promotions.index', compact('availableBonuses', 'tipo'));
    }

    /**
     * Display pendentes page
     *
     * @return \View
     */
    public function activeBonuses() {
        $activeBonuses = $this->authUser->activeBonuses()->get();
        $this->formatBonusValue($activeBonuses);
        return view('portal.promotions.active_bonuses', compact('activeBonuses'));
    }
    /**
     * Display utilizados page
     *
     * @return \View
     */
    public function consumedBonuses() {
        $consumedBonuses = $this->authUser->consumedBonuses()->get();
        $this->formatBonusValue($consumedBonuses);
        return view('portal.promotions.consumed_bonuses', compact('consumedBonuses'));
    }

    public function redeemBonus($bonus_id) {
        if (!$this->authUser->redeemBonus($bonus_id))
            Session::flash('error', 'Não é possível resgatar o bonus.');
        else
            Session::flash('success', 'Bonus resgatado com sucesso.');

        return Response::redirectTo('/promocoes');
    }

    public function cancelBonus($bonus_id) {
        if (!$this->authUser->cancelBonus($bonus_id))
            Session::flash('error', 'Não é possível cancelar o bonus.');
        else
            Session::flash('success', 'Bonus cancelado.');

        return Response::redirectTo('/promocoes/activos');
    }


}
