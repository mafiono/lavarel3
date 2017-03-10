<?php

namespace App\Bets\Bets;

use App\GlobalSettings;
use App\Models\SportsMarketsMultiple;
use App\UserBetStatus;
use App\UserSession;
use Auth;
use App\Bets\Bets\Events\BetslipEvent;

class BetslipBet extends Bet
{
    private $rid;

    public static function make(array $bet, $betslip_id, $user = null, $sessionId = null)
    {
        $user = $user ?: Auth::user();

        $newBet = new static;

        $newBet->user_id = $user->id;
        $newBet->api_bet_type = 'betportugal';
        $newBet->api_bet_id = '';
        $newBet->api_transaction_id = '';
        $newBet->rid = $bet['rid'];
        $newBet->amount = $bet['amount'];
        $newBet->tax = GlobalSettings::getTax();
        $newBet->amount_taxed = $newBet->amount*$newBet->tax;
        $newBet->type = $bet['type'];
        $newBet->odd = $newBet->getOdds($bet);
        $newBet->status = 'waiting_result';
        $newBet->user_session_id = isset($sessionId) && $sessionId !== null ? $sessionId : UserSession::getSessionId();
        $newBet->user_betslip_id = $betslip_id;

        $markets = [];
        foreach ($bet['events'] as $event) {
            $newBet->events->add(BetslipEvent::make($event));
            $markets[] = $event['marketId'];
        }
        $newBet->market_id = SportsMarketsMultiple::getId($markets);

        return $newBet;
    }

    private function getOdds(array $bet)
    {
        if ($bet['type'] === 'simple')
            return (float)$bet['events'][0]['odds'];

        return (float) array_reduce($bet['events'], function ($carry, $event) {
            return is_null($carry) ? $event['odds'] : $carry * $event['odds'];
        });
    }

    public function getRid()
    {
        return $this->rid;
    }
}
