<?php

namespace App\Http\Controllers\Casino;

use App\Http\Controllers\Controller;
use Auth;
use Predis\ClientException;
use Request;
use Session;
use View;

class CasinoGamesController extends Controller
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
        $url = 'aa381523-0b6b-44ca-ac39-ce18918bae4c.mock.pstmn.io/categories';

        return $this->makeRequestGeneric($url);
    }

    public function getCategoriesGames($id)
    {
        $url = 'aa381523-0b6b-44ca-ac39-ce18918bae4c.mock.pstmn.io/categories/' . $id . '/games';

        return $this->makeRequestGeneric($url);
    }
    public function getGame($id)
    {
        $url = 'aa381523-0b6b-44ca-ac39-ce18918bae4c.mock.pstmn.io/categories/games/' . $id . '';

        return $this->makeRequestGeneric($url);
    }

    private function makeRequestGeneric($url)
    {
        $baseApi = config('app.core_api_url');

        $client = new Client([
            'verify' => false,
            'json' => true,
            'proxy' => false,
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
