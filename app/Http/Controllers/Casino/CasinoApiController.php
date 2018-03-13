<?php

namespace App\Http\Controllers\Casino;

use App\Http\Controllers\Controller;
use Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Session;
use View;

class CasinoApiController extends Controller
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

    public function getCategoriesList()
    {
        $url = '/categories';

        return $this->makeRequestGeneric($url);
    }

    public function getCategoriesGames($id)
    {
        $device = $this->request->get('device');
        $url = "/categories/$id/games?device=$device";
        return $this->makeRequestGeneric($url);
    }
    public function getGame($id)
    {
        $url = '/categories/games/' . $id;

        return $this->makeRequestGeneric($url);
    }

    private function makeRequestGeneric($url)
    {
        $baseApi = config('app.core_api_url') . '/api/v1';

        $client = new Client([
            'verify' => false,
            'json' => true,
        ]);

        try {
            $obj = $client->get($baseApi . $url);
            $resp = $obj->getBody()->getContents();

            return response($resp, 200, [
                'Content-Type' => 'application/json',
            ]);
        } catch (ClientException $e) {
            $resp = $e->getResponse()->getBody()->getContents();

            return response($resp, $e->getCode(), [
                'Content-Type' => 'application/json',
            ]);
        }
    }
}
