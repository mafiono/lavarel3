<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Response;
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
    protected function checkUser($username,$promo){

        if ($this->userHasPromoCode($username, $promo))
        {return 'ok';}
        return abort(404);
    }

    public function academiaDeApostas(Request $request)
    {
        $inputs = $request->all();
        $username = $inputs['username'];
        $this->checkUser($username,'866391');
    }
}