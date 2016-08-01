<?php

namespace App\Bets\Verifier;


use Carbon\Carbon;
use Exception;

class EventVerifier
{
    private $selection;

    private $event;

    public function __construct($selection, $event)
    {
        $this->selection = $selection;

        $this->event = $event;
    }

    public static function make($selection, $event)
    {
        return new static($selection, $event);
    }

    public function verify()
    {
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

    private function checkStatus()
    {
        if ($this->selection->trading_status != 'Trading')
            throw new Exception("Apostas suspensas para este evento");
    }

    private function checkMarketStatus()
    {
        if ($this->selection->market->trading_status != "Open")
            throw new Exception("Mercado suspenso");
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
        if ($this->selection->market->fixture->is_over)
            throw new Exception("Jogo terminado");
    }

    private function checkMarketExpire()
    {
        if (Carbon::parse($this->selection->market->expiry_utc, 'UTC') <= Carbon::now()->tz('UTC'))
            throw new Exception("Mercado expirado");
    }

    private function checkMarketId()
    {
        if ($this->selection->market->id != $this->event->api_market_id)
            throw new Exception("Mercado inválido");
    }

    private function checkOdds()
    {
        if ($this->selection->decimal != $this->event->odd)
            throw new Exception("Cotas alteradas");
    }

    private function checkResult()
    {
        if ($this->selection->result)
            throw new Exception("O resultado já é conhecido");
    }

    private function checkGameId()
    {
        if ($this->selection->market->fixture_id != $this->event->api_game_id)
            throw new Exception("Jogo inválido");
    }

    private function checkGameDate()
    {
        $fixtureDate = Carbon::parse($this->selection->market->fixture->start_time_utc, 'UTC');
        $eventDate = Carbon::parse($this->event->game_date, 'UTC');

        if ($fixtureDate != $eventDate)
            throw new Exception('Data do jogo inválida');
    }
}