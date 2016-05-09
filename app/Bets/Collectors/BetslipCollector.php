<?php

namespace App\Bets\Collectors;

use Illuminate\Http\Request;
use App\Bets\Bets\BetslipBet;
use App\Bets\Bookie\BetBookie;
use App\Bets\Validators\BetslipBetValidator;
use App\User;
use Exception;
use DB;
use Auth;

class BetslipCollector extends BetCollector
{
    private $user;
    private $response;
    private $request;

    public function __construct(User $user, Request $request)
    {
        $this->user = $user ?: Auth::user();
        $this->request = $request;
        $this->bets = [];
        $this->response = ['data' => []];
    }

    public function collectBets()
    {
        foreach($this->request['bets'] as $bet)
            $this->bets[] = new BetslipBet($this->user, $bet);

        return $this->bets;
    }

    public function processBets()
    {
        DB::transaction(function () {
            foreach ($this->bets as $bet) {
                try {
                    (new BetslipBetValidator($bet))->validate();
                    BetBookie::placeBet($bet);
                    $this->addToResponse($bet->getRid());
                } catch (Exception $e) {
                    $this->addToResponse($bet->getRid(), $e->getMessage(), 1);
                    continue;
                }
            }
        });
        return $this->response;
    }

    private function addToResponse($rid, $msg='Sucesso.', $code=0)
    {
        array_push($this->response['data'], [
            'rid' => $rid,
            'errorMsg' => $msg,
            'code' => $code,
        ]);
    }

}