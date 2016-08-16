<?php

namespace App\Http\Controllers\Portal;

use App\Bonus\SportsBonusException;
use Exception;
use Illuminate\Support\Facades\Lang;
use SportsBonus;
use App\Http\Controllers\Controller;
use Session, View, Response, Auth;
use Illuminate\Http\Request;

class PromotionsController extends Controller
{
    protected $authUser;

    protected $request;

    protected $userSessionId;

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

    public function index($tipo = null)
    {
        if ($tipo == null || $tipo == 'desportos') {
            $availableBonuses = SportsBonus::getAvailable();
        } else if ($tipo == 'casino') {
            $availableBonuses = [];
        } else {
            $availableBonuses = []; // rede de amigos
        }

//        return $availableBonuses;
        return view('portal.promotions.index', compact('availableBonuses', 'tipo'));
    }


    public function activeBonuses() {
        $activeBonuses = SportsBonus::getActive();

        return view('portal.promotions.active_bonuses', compact('activeBonuses'));
    }


    public function consumedBonuses()
    {
        $consumedBonuses = SportsBonus::getConsumed();

        return view('portal.promotions.consumed_bonuses', compact('consumedBonuses'));
    }

    public function redeemBonus($bonusId)
    {
        try {
            SportsBonus::redeem($bonusId);
            Session::flash('success', Lang::get('bonus.redeem.success'));
        } catch (SportsBonusException $e) {
            Session::flash('error', $e->getMessage());
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());//Lang::get('bonus.system.error'));
        }

        return Response::redirectTo('/promocoes');
    }

    public function cancelBonus($bonusId) {

        try {
            SportsBonus::cancel($bonusId);
            Session::flash('success', Lang::get('bonus.cancel.success'));
        } catch (SportsBonusException $e) {
            Session::flash('error', $e->getMessage());
        } catch (Exception $e) {
            Session::flash('error', Lang::get('bonus.system.error'));
        }

        return Response::redirectTo('/promocoes/activos');
    }


}
