<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function userHasPromoCode($username, $promo)
    {
        return User::Where(function ($query) use ($promo) {
            return $query->where('promo_code', '=', $promo)
                ->orWhere('promo_code', 'like', $promo . '_%');
        })->whereUsername($username)->exists();
    }

    protected function userHasPromoCodeAndDeposited($username, $promo)
    {
        return User::Where(function ($query) use ($promo) {
                return $query->where('promo_code', '=', $promo)
                    ->orWhere('promo_code', 'like', $promo . '_%');
            })
            ->whereUsername($username)
            ->whereHas('transactions', function ($query) {
                return $query->whereIn('origin', ['bank_transfer', 'cc', 'mb', 'meo_wallet', 'paypal'])
                    ->where('debit', '>', 0)
                    ->whereStatusId('processed');
            })
            ->exists();
    }

    public function academiaDeApostas(Request $request)
    {
        if ($this->userHasPromoCodeAndDeposited($request->username, '866391'))
            return 'deposited';

        if ($this->userHasPromoCode($request->username, '866391')) {
            return 'ok';
        }
        return abort(404);
    }
}