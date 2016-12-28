<?php

namespace App\Http\Controllers\Portal;

use App\Bonus\SportsBonusException;
use App\Http\Traits\GenericResponseTrait;
use Exception;
use Illuminate\Support\Facades\Lang;
use SportsBonus;
use App\Http\Controllers\Controller;
use Session, View, Response, Auth;
use Illuminate\Http\Request;

class PromotionsController extends Controller
{
    use GenericResponseTrait;

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

    public function index()
    {
        $availableSportBonuses = SportsBonus::getAvailable();
        $availableCasinoBonuses = [];

        return view('portal.promotions.index', compact('availableSportBonuses', 'availableCasinoBonuses'));
    }


    public function activeBonuses()
    {
        $activeSportBonuses = SportsBonus::getActive();
        $activeCasinoBonuses = [];

        return view('portal.promotions.active_bonuses', compact('activeSportBonuses', 'activeCasinoBonuses'));
    }


    public function consumedBonuses()
    {
        $consumedSportBonuses = SportsBonus::getConsumed();
        $consumedCasinoBonuses = [];

        return view('portal.promotions.consumed_bonuses', compact('consumedSportBonuses', 'consumedCasinoBonuses'));
    }

    public function redeemBonus($bonusId)
    {
        try {
            SportsBonus::redeem($bonusId);
            return $this->respType('success', Lang::get('bonus.redeem.success'));
        } catch (SportsBonusException $e) {
            return $this->respType('error', $e->getMessage());
        } catch (Exception $e) {
            return $this->respType('error', $e->getMessage());//Lang::get('bonus.system.error'));
        }
    }

    public function cancelBonus($bonusId) {
        try {
            if (!SportsBonus::hasId($bonusId))
                throw new SportsBonusException(Lang::get('bonus.cancel.error'));

            SportsBonus::cancel($bonusId);
            return $this->respType('success', Lang::get('bonus.cancel.success'));
        } catch (SportsBonusException $e) {
            return $this->respType('error', $e->getMessage());
        } catch (Exception $e) {
            return $this->respType('error', Lang::get('bonus.system.error'));
        }
    }
}
