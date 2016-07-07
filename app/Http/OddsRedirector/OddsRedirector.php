<?php

namespace App\Http\OddsRedirector;


use GuzzleHttp\Client;
use Illuminate\Http\Request;

class OddsRedirector
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function to($pathname)
    {
        $client = new Client();

        $host = env('ODDS_SERVER');

        $request = $client->request(
            'POST',
            $host . $pathname,
            ['form_params' => $this->request->all()]
        );

        return response($request->getBody())->header('Content-Type', 'application/json');
    }
}