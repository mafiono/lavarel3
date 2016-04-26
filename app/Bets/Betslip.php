<?php

namespace App\Bets;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use DB;

class Betslip
{
    private $bets;
    private $user;
    private $response;
    private $request;

    public function __construct(Request $request)
    {
        $this->bets = [];
        $this->user = Auth::user();
        $this->response = ['data' => []];
        $this->request = $request;
    }

    public function PlaceBets()
    {
        DB::transaction(function () {
            foreach ($this->request['bets'] as $betRequest) {
                $bet = new Bet($betRequest, $this->user);
                try {
                    (new BetValidator($bet))->validate();
                    $this->addBetStatus($betRequest['rid']);
                } catch (Exception $e) {
                    $this->addBetStatus($betRequest['rid'], $e->getMessage(), 1);
                    continue;
                }
                $bet->placeBet();
            }
        });
        return $this->response;
    }

    private function addBetStatus($rid, $msg='Sucesso.', $code=0)
    {
        array_push($this->response['data'], [
            'rid' => $rid,
            'errorMsg' => $msg,
            'code' => $code,
        ]);
    }

}