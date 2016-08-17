<?php

namespace App\Bets\Verifier;


use App\Bets\Bets\Bet;
use Exception;
use GuzzleHttp\Client;

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

            EventVerifier::make($selection, $event)->verify();
        }
    }

    private function getSelection($id)
    {
        foreach ($this->selections as $selection)
            if ($selection->id == $id)
                return $selection;

        throw new Exception("Evento desconhecido");
    }

    private function fetchSelections(Bet $bet)
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL =>  env('ODDS_SERVER') . 'selections',
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POSTFIELDS => $this->selectionsQuery($bet),
            CURLOPT_TIMEOUT => 45
        ]);

        $ret = curl_exec($ch);

        return json_decode($ret)->selections;
    }

    private function selectionsQuery(Bet $bet)
    {
        $query = ['ids' => '', 'with' => 'market.fixture,result'];

        $ids = [];

        foreach ($bet->events as $event)
            $ids[] = $event->api_event_id;

        $query['ids'] = implode(',', $ids);

        return $query;
    }
}