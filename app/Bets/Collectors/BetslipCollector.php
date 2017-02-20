<?php

namespace App\Bets\Collectors;

use App\Bets\Bets\BetException;
use App\UserBetslip;
use Exception;
use Illuminate\Http\Request;
use App\Bets\Bets\BetslipBet;
use App\Bets\Bookie\BetBookie;
use App\Bets\Validators\BetslipBetValidator;
use DB;

class BetslipCollector extends BetCollector
{

    public function __construct(Request $request, $user = null)
    {
        parent::__construct($request, $user);
    }

    public function collect()
    {
        $this->checkValidBetslip();

        $this->checkBetslipEmpty();

        $newBetslip = UserBetslip::create(['user_id' => $this->user->id]);

        foreach($this->request['bets'] as $bet)
            $this->bets[] = BetslipBet::make($bet, $newBetslip->id, $this->user);

        return $this;
    }

    public function process()
    {
        DB::transaction(function () {

            foreach ($this->bets as $bet)
                $this->processBet($bet);

        });

        return $this->response;
    }

    private function processBet($bet)
    {
        try {
            BetslipBetValidator::make($bet)->validate();

            BetBookie::placeBet($bet);

            $this->responseAdd($bet->getRid());
        } catch (BetException $e) {
            $this->responseAdd($bet->getRid(), $e->getMessage(), 1, ($e->getEventId() ?: ''));
        }
    }

    private function responseAdd($rid, $msg = 'Sucesso.', $code = 0, $eventId = null)
    {
        $response = [
            'rid' => $rid,
            'errorMsg' => $msg,
            'code' => $code,
            'userId' => $this->user->id,
        ];

        if ($eventId) {
            $response['eventId'] = $eventId;
        }

        $this->response['bets'][] = $response;
    }

    private function checkValidBetslip()
    {
        if (!isset($this->request['bets']) || !is_array($this->request['bets']))
            throw new Exception('Invalid betslip');
    }

    private function checkBetslipEmpty()
    {
        if (!count($this->request['bets']))
            throw new Exception('Betslip empty');
    }

}