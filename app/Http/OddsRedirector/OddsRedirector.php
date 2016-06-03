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

    public function to($pathname, $id = null)
    {
        $client = new Client();

        $request = $client->request(
            'POST',
            env('ODDS_SERVER', 'http://localhost:6969') . '/' . $pathname . ($id ? '/' . $id : ''),
            ['form_params' => $this->request->all()]
        );

        return $request->getBody();
    }
}