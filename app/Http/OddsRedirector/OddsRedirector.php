<?php

namespace App\Http\OddsRedirector;


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
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL =>  env('ODDS_SERVER') . $pathname,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POSTFIELDS => $this->request->all(),
            CURLOPT_TIMEOUT => 300
        ]);
        if (env('CURL_PROXY', false)) {
            curl_setopt($ch, CURLOPT_PROXY, env('CURL_PROXY'));
        }

        $ret = curl_exec($ch);

        return response($ret)->header('Content-Type', 'application/json');
    }
}