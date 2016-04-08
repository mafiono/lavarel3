<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Session, View, Response, Auth, Mail, Validator;
use Illuminate\Http\Request;
use App\Bonus;
use App\UserBonus;
use App\ListSelfExclusion;

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

    protected function formatUserBonusValues($bonuses) {
        foreach ($bonuses as $userBonus) {
            $userBonus->bonus->value = floor($userBonus->bonus->value);
            if ($userBonus->bonus->value_type === 'percentage')
                $$userBonus->bonus->value .= '%';
        }
    }

    /**
     * Display index page
     *
     * @return \View
     */
    public function index($tipo = null) {
        if ($tipo == null || $tipo == 'desportos') {
            $availableBonuses = UserBonus::availableBonuses($this->authUser);
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
        $activeBonuses = UserBonus::activeBonuses($this->authUser);
        return view('portal.promotions.active_bonuses', compact('activeBonuses'));
    }
    /**
     * Display utilizados page
     *
     * @return \View
     */
    public function consumedBonuses() {
        $consumedBonuses = UserBonus::consumedBonuses($this->authUser);
        return view('portal.promotions.consumed_bonuses', compact('consumedBonuses'));
    }

    public function redeemBonus($bonus_id) {
        $data['document_number'] = $this->authUser->profile->document_number;
        $selfExclusion = ListSelfExclusion::validateSelfExclusion($data)
            || $this->authUser->getSelfExclusion();

        if ($selfExclusion)
            Session::flash('error', 'Utilizadores auto-excluidos não podem resgatar bónus.');
        else if (!UserBonus::redeemBonus($this->authUser, $bonus_id))
            Session::flash('error', 'Não é possível resgatar o bónus.');
        else
            Session::flash('success', 'Bónus resgatado com sucesso.');

        return Response::redirectTo('/promocoes');
    }

    public function cancelBonus($id) {
        $data['document_number'] = $this->authUser->profile->document_number;
        $selfExclusion = ListSelfExclusion::validateSelfExclusion($data)
            || $this->authUser->getSelfExclusion();

        if ($selfExclusion)
            Session::flash('error', 'Utilizadores auto-excluidos não podem cancelar bónus.');
        else if (!UserBonus::cancelBonus($this->authUser, $id))
            Session::flash('error', 'Não é possível cancelar o bónus.');
        else
            Session::flash('success', 'Bónus cancelado.');

        return Response::redirectTo('/promocoes/activos');
    }


}
