<?php

namespace App\Betslip;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

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
        foreach ($this->request['bets'] as $bet) {
            try {
                (new BetValidator(new Bet($bet)))->validate();
                $this->addBetStatus($bet['rid']);
            } catch (Exception $e) {
                $this->addBetStatus($bet['rid'], $e->getMessage(), 1);
            }
        }
        return $this->response;
    }

    protected function addBetStatus($rid, $errorMsg='Sucesso.', $code=0)
    {
        array_push($this->response['data'], [
            'rid' => $rid,
            'errorMsg' => $errorMsg,
            'code' => $code,
        ]);
    }

    protected function User() {
        return $this->user;
    }
}