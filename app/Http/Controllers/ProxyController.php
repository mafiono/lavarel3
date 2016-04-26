<?php
/**
 * This is a prototype.
 */
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;
use WebSocket\Client;
use Input;
use Cache;

class ProxyController extends Controller {

    public function proxy() {
        $client = new Client("ws://swarm-partner.betconstruct.com", ['timeout' => 10]);
        $req = ["command" => "request_session", "params" => ["site_id" => 234, "language" => "por_2"] ];
        $client->send(json_encode($req));
        $client->receive();
        $req = ["command" => "get",
            "params" => [
                "source" => "betting"
//              "what" => ["sport" => []]
            ]
        ];

        if (Request::has("what")) {
            $what = Request::input("what");
            $what = json_decode($what);
            $req["params"]["what"] = $what;
        }

        if (Request::has("where")) {
            $where = Request::input("where");
            $where = json_decode($where);
            $req["params"]["where"] = $where;
        }
        $client->send(json_encode($req));
        //dd($client->receive());

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        return Response::json($client->receive());
    }

    public function proxy2() {
        $cacheKey = serialize(Input::all());
        if (false && Cache::has($cacheKey)) {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST');
            return Response::json(Cache::get($cacheKey));
        } else {
            $client = new Client("ws://swarm-partner.betconstruct.com", ['timeout' => 10]);
            $req = ["command" => "request_session", "params" => ["site_id" => 234, "language" => "por_2"]];
            $client->send(json_encode($req));
            $client->receive();
            $req = ["command" => "get",
                "params" => [
                    "source" => "betting"
                ]
            ];

            if (Request::has("what")) {
                $what = Request::input("what");
                $what = json_decode($what);
                $req["params"]["what"] = $what;
            }

            if (Request::has("where")) {
                $where = Request::input("where");
                $where = json_decode($where);
                $req["params"]["where"] = $where;
            }

            $client->send(json_encode($req));
            Cache::put($cacheKey, $client->receive(), 1);

            return Response::make(utf8_decode(Cache::get($cacheKey)))
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST')
                ->header('Content-Type', 'application/json');
        }
    }

}