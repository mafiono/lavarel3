<?php

namespace App\Bets\Verifier;


use App\Bets\Bets\BetException;
use App\GlobalSettings;
use App\UserBet;
use Carbon\Carbon;

class EventVerifier
{
    private $event;

    private $selection;

    private $bet;

    public function __construct($bet, $event, $selection)
    {
        $this->bet = $bet;

        $this->event = $event;

        $this->selection = $selection;
    }

    public static function make($bet, $event, $selection)
    {
        return new static($bet, $event, $selection);
    }

    public function verify()
    {
        $this->checkUpperBetLimit();

        $this->checkStatus();

        $this->checkResult();

        $this->checkOdds();

        $this->checkStatus();

        $this->checkMarketId();

        $this->checkMarketStatus();

        $this->checkExpire();

        $this->checkGameId();

        $this->checkGameDate();

    }

    private function checkUpperBetLimit()
    {
        $betType = $this->bet->type;

        $group = $this->selection->market->fixture->competition->betting_group;

        $maxAmount = GlobalSettings::getBetUpperLimit($betType === 'simple' ? $group : 'g1');

        $betAmount = $this->bet->amount;

        if ($betType === 'simple') {
            $betAmount += UserBet::betAmountFromSimpleBetsForEvent($this->bet->user_id, $this->event->api_event_id);
        }

        if ($betAmount > $maxAmount) {
            throw new BetException('O limite superior é de ' . $maxAmount . ' euros');
        }
    }

    private function checkStatus()
    {
        if ($this->selection->trading_status != 'Trading')
            throw new BetException("Apostas suspensas para este evento", $this->selection->id);
    }

    private function checkMarketStatus()
    {
        if ($this->selection->market->trading_status != "Open")
            throw new BetException("Mercado suspenso", $this->selection->id);
    }

    private function checkExpire()
    {
        if ($this->selection->market->in_play) {
            $this->checkGameOver();

            return;
        }

        $this->checkMarketExpire();
    }

    private function checkGameOver()
    {
        if (!$this->selection->market->fixture->in_play)
            throw new BetException("Jogo terminado ou suspenso", $this->selection->id);
    }

    private function checkMarketExpire()
    {
        if (Carbon::parse($this->selection->market->expiry_utc, 'UTC') <= Carbon::now()->tz('UTC'))
            throw new BetException("Mercado expirado", $this->selection->id);
    }

    private function checkMarketId()
    {
        if ($this->selection->market->id != $this->event->api_market_id)
            throw new BetException("Mercado inválido", $this->selection->id);
    }

    private function checkOdds()
    {
        if ($this->selection->decimal != $this->event->odd)
            throw new BetException("Cotas alteradas", $this->selection->id);
    }

    private function checkResult()
    {
        if ($this->selection->result)
            throw new BetException("O resultado já é conhecido", $this->selection->id);
    }

    private function checkGameId()
    {
        if ($this->selection->market->fixture_id != $this->event->api_game_id)
            throw new BetException("Jogo inválido", $this->selection->id);
    }

    private function checkGameDate()
    {
        $fixtureDate = Carbon::parse($this->selection->market->fixture->start_time_utc, 'UTC');
        $eventDate = Carbon::parse($this->event->game_date, 'UTC');

        if ($fixtureDate != $eventDate)
            throw new BetException('Data do jogo inválida', $this->selection->id);
    }
}