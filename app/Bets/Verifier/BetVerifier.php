<?php

namespace App\Bets\Verifier;

use App\Bets\Bets\Bet;
use App\Bets\Bets\BetException;
use App\Bets\Models\Selection;

class BetVerifier
{
    private $selections;

    private $bet;

    public function __construct(Bet $bet)
    {
        $this->bet = $bet;

        $this->selections = $this->fetchSelections($bet);
    }

    public static function make(Bet $bet)
    {
        return new static($bet);
    }

    public function verify()
    {
        foreach ($this->bet->events as $event)
        {
            $selection = $this->getSelection($event->api_event_id);

            EventVerifier::make($this->bet, $event, $selection)->verify();
        }
    }

    private function getSelection($id)
    {
        foreach ($this->selections as $selection)
            if ($selection->id == $id)
                return $selection;

        throw new BetException("Evento desconhecido", $id);
    }

    private function fetchSelections(Bet $bet)
    {
        $ids = [];

        foreach ($bet->events as $event)
            $ids[] = $event->api_event_id;

        return Selection::ids($ids)
            ->with('result', 'market.fixture.competition')
            ->get();
    }
}