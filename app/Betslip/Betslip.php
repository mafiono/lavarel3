<?php

namespace App\Betslip;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\CountValidator\Exception;

class Betslip
{
    protected $bets;
    protected $user;
    protected $betStatuses;
    protected $request;

    public function __construct(Request $request)
    {
        $this->bets = [];
        $this->user = Auth::user();
        $this->request = $request;
    }

    public function PlaceBets()
    {
        foreach ($this->request['bets'] as $bet) {
            try {
                (new BetValidator(new Bet($bet)))->validate();
                $this->addBetStatus($bet['id']);
            } catch (Exception $e) {
                $this->addBetStatus($bet['id'], $e->getMessage(), 1);
            }
        }
        return $this->betStatuses;
    }

    protected function addBetStatus($id, $errorMsg='Sucesso.', $code=0)
    {
        array_push($betStatuses, [
            'id' => $id,
            'errorMsg' => $errorMsg,
            'code' => $code,
        ]);
    }

    protected function User() {
        return $this->user;
    }
}