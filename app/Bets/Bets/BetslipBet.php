<?php

namespace App\Bets\Bets;

use App\User;
use App\UserBet;
use App\UserSession;
use Auth;
use Carbon\Carbon;
use App\Bets\Bets\Events\BetslipEvent;

class BetslipBet extends UserBet
{
    private $rid;
    public function __construct(User $user, array $bet)
    {
        $user = $user ?: Auth::user();
        $this->user_id = $user->id;
        $this->api_bet_type = 'betportugal';
        $this->api_bet_id = '';
        $this->api_transaction_id = '';
        $this->rid = $bet['rid'];
        $this->amount = $bet['amount'];
        $this->type = $bet['type'];
        $this->odd = $this->oddFromBet($bet);
//        $this->gameDate = $this->gameDateFromBet($bet);
        $this->status = 'waiting_result';
        $this->user_session_id = UserSession::getSessionId();

        foreach ($bet['events'] as $event)
            $this->events->add(new BetslipEvent($event));

        parent::__construct();
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
