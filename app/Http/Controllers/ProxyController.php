<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;
use WebSocket\Client;


class ProxyController extends Controller {
    public function proxy() {
        $client = new Client("ws://swarm-partner.betconstruct.com");
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

}