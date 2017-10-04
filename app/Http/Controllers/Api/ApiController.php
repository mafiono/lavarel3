<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use App\UserTransaction;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    protected function userHasPromoCode($username, $promo) {
        return User::wherePromoCode($promo)->whereUsername($username)->exists();
    }

    protected function userHasPromoCodeAndDeposited($username,$promo)
    {
        if(User::wherePromoCode($promo)->whereUsername($username)->exists()){
            $user = User::wherePromoCode($promo)->whereUsername($username)->first();
            if(UserTransaction::where('origin','!=',"casino_bonus")->where('origin','!=','sport_bonus')->whereUserId($user->id)->whereStatusId('processed')->exists())
            {
                return true;
            }
        }
        return false;
    }

    public function academiaDeApostas(Request $request)
    {
        if($this->userHasPromoCodeAndDeposited($request->username, '866391'))
            return 'deposited';

        if ($this->userHasPromoCode($request->username, '866391')) {
            return 'ok';
    }
        return abort(404);
    }
}