<?php

namespace App\Bets;

use App\User;
use App\UserBets;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon\Carbon;

class BetslipBet extends Bet
{
    private $status = 'waiting_result';

    public function __construct(array $bet, User $user)
    {
        $this->user = $user ?: Auth::user();
        $this->bet = $bet;

    }

    public function getApiType()
    {
        return 'betportugal';
    }

    public function getApiId()
    {
        return '';
    }

    public function getApiTransactionId() {
        return '';
    }

    public function getRid()
    {
        return $this->bet['rid'];
    }

    public function getAmount()
    {
        return (float) $this->bet['amount'];
    }

    public function getType()
    {
        return $this->bet['type'];
    }

    public function getOdd()
    {
        if ($this->bet['type'] === 'simple')
            return (float)$this->bet['events'][0]['odd'];

        return array_reduce($this->bet['events'], function($carry, $event) {
            return is_null($carry)?$event['odd']:$carry*$event['odd'];
        });
    }

    public function getStatus() {
        return $this->status;
    }

    public function getGameDate() {
        if ($this->bet['type'] === 'simple')
            return Carbon::createFromTimestamp($this->bet['events'][0]['gameDate']);

        return array_reduce($this->bet['events'], function($carry, $event) {
            return is_null($carry)?Carbon::createFromTimestamp($event['gameDate']):max($carry, Carbon::createFromTimestamp($event['gameDate']));
        });
    }

}