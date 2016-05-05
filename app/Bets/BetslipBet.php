<?php

namespace App\Bets;

use App\User;
use App\UserBets;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon\Carbon;

class BetslipBet extends Bet
{

    public function __construct(User $user, array $bet)
    {
        $this->user = $user ?: Auth::user();
        $this->apiType = 'betportugal';
        $this->apiId = '';
        $this->apiTransId = '';
        $this->rid = $bet['rid'];
        $this->amount = $bet['amount'];
        $this->type = $bet['type'];
        $this->odd = $this->oddFromBet($bet);
        $this->gameDate = $this->gameDateFromBet($bet);
        $this->status = 'waiting_result';
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
}
