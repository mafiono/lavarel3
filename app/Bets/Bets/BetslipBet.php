<?php

namespace App\Bets\Bets;

use App\GlobalSettings;
use App\User;
use App\UserBet;
use App\UserSession;
use Auth;
use Carbon\Carbon;
use App\Bets\Bets\Events\BetslipEvent;

class BetslipBet extends UserBet
{
    private $rid;

    public static function make($user, array $bet, $betslip_id)
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
        $newBet->odd = $newBet->oddFromBet($bet);
        $newBet->status = 'waiting_result';
        $newBet->user_session_id = UserSession::getSessionId();
        $newBet->user_betslip_id = $betslip_id;

        foreach ($bet['events'] as $event)
            $newBet->events->add(new BetslipEvent($event));

        return $newBet;
    }

    private function gameDateFromBet(array $bet) {
        if ($bet['type'] === 'simple')
            return Carbon::createFromTimestamp($bet['events'][0]['gameDate']);

        return array_reduce($bet['events'], function($carry, $event) {
            return is_null($carry)?Carbon::createFromTimestamp($event['gameDate']):max($carry, Carbon::createFromTimestamp($event['gameDate']));
        });
    }

    private function oddFromBet(array $bet) {
        if ($bet['type'] === 'simple')
            return (float)$bet['events'][0]['odd'];

        return (float) array_reduce($bet['events'], function ($carry, $event) {
            return is_null($carry) ? $event['odd'] : $carry * $event['odd'];
        });
    }

    public function getRid() {
        return $this->rid;
    }

}
