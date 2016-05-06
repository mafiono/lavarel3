<?php


namespace App\Bets;

use App\UserBet;

class BetResultFaker extends Bet
{
    public function __construct(UserBet $userBet)
    {
        $this->user = $userBet->user();
        $this->apiType = $userBet->api_bet_type;
        $this->apiId = $userBet->api_bet_id;
        $this->apiTransId = '';
        $this->amount = $userBet->amount;
        $this->type = $userBet->bet_type;
        $this->odd = $userBet->odd;
//        $this->gameDate = $this->gameDateFromBet($bet);
        $this->status = $userBet->status;
    }
}