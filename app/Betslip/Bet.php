<?php

namespace App\Betslip;

use App\UserBetStatus;
use App\UserBetTransactions;
use App\UserBets;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use Carbon\Carbon;

class Bet
{
    private $user;
    private $bet;
    private $id;
    private $gameDate;
    private $status = 'waiting_result';

    public function __construct($bet)
    {
        $this->user = Auth::user();
        $this->bet = $bet;

    }

    public function getUser()
    {
        return $this->user;
    }

    public function getApiBetType()
    {
        return 'betportugal';
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
        return (float)$this->bet['amount'];
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

    public function placeBet() {
        $userBet = UserBets::createBet($this);
        $userBetStatus = UserBetStatus::setBetStatus($userBet->status, $userBet->id, $userBet->user_session_id);
        $balances = BetCollector::charge($this);
        UserBetTransactions::createTransaction($this, 'withdrawal', $userBetStatus->id, $balances);
    }

}