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
    protected function userHasPromoCode($username, $promo) {
        return User::wherePromoCode($promo)->whereUserName($username)->exists();
    }

    public function academiaDeApostas(Request $request)
    {
        if ($this->userHasPromoCode($request->username, '866391')) {
            return 'ok';
    }
        return abort(404);
    }
}