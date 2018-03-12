<?php

namespace App\Http\Controllers\Casino;

use App\Models\CasinoSession;
use App\Models\CasinoToken;
use App\Models\CasinoGame;
use App\Http\Controllers\Controller;
use App\Netent\Netent;
use App\UserSession;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Auth;
use Log;

class CasinoSideBarGames extends Controller{
    public function getSideBarList(){
        $url = '/categories/games/side';

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