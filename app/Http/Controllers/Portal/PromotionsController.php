<?php

namespace App\Http\Controllers\Portal;

use App\Bonus\Casino\CasinoBonusException;
use App\Bonus\Sports\SportsBonusException;
use App\Http\Traits\GenericResponseTrait;
use CasinoBonus;
use Exception;
use Illuminate\Support\Facades\Lang;
use SportsBonus;
use App\Http\Controllers\Controller;
use Session, View, Auth;
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

    public function redeemSportBonus($bonusId)
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

    public function cancelSportBonus($bonusId) {
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

    public function redeemCasinoBonus($bonusId)
    {
        try {
            CasinoBonus::redeem($bonusId);
            return $this->respType('success', Lang::get('bonus.redeem.success'));
        } catch (CasinoBonusException $e) {
            return $this->respType('error', $e->getMessage());
        } catch (Exception $e) {
            return $this->respType('error', $e->getMessage());//Lang::get('bonus.system.error'));
        }
    }

    public function cancelCasinoBonus($bonusId) {
        try {
            if (!CasinoBonus::hasId($bonusId))
                throw new CasinoBonusException(Lang::get('bonus.cancel.error'));

            CasinoBonus::cancel($bonusId);
            return $this->respType('success', Lang::get('bonus.cancel.success'));
        } catch (CasinoBonusException $e) {
            return $this->respType('error', $e->getMessage());
        } catch (Exception $e) {
            return $this->respType('error', Lang::get('bonus.system.error'));
        }
    }
}
