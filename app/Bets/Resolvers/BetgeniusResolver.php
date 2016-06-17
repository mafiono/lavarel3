<?php

namespace App\Bets\Resolvers;


use App\Bets\Bets\Bet;
use App\UserBetEvent;
use DB;
use GuzzleHttp\Client;

class BetgeniusResolver
{
    private $events;

    private $statuses = [
        'None' => 'cancelled',
        'Winner' => 'won',
        'Pushed' => 'pushed',
        'Loser' => 'lost',
        'Placed' => 'placed',
        'Partial' => 'partial',
    ];

    public static function make()
    {
        return new static;
    }

    public function collect()
    {
        $this->events = UserBetEvent::past(2)
            ->unresolved()
            ->get();

        return $this;
    }

    public function resolve()
    {
        foreach ($this->events as $event)
        {
            $results = $this->fetchResult($event);

            if (!count($results))
                continue;

            DB::transaction(function () use ($event, $results) {
                $this->resolveEvent($event, $results[0]);
            });
        }
    }

    private function fetchResult(UserBetEvent $event)
    {
        $client = new Client();

        $request = $client->request(
            'POST',
            env('ODDS_SERVER', 'http://localhost:6969') . '/results',
            ['form_params' => $this->resultQuery($event)]
        );

        return json_decode($request->getBody())->results;
    }

    private function resultQuery(UserBetEvent $event)
    {
        return ['selections' => $event->api_event_id];
    }

    private function resolveEvent(UserBetEvent $event, $result)
    {
        $status = $this->statuses[$result->result_status];

        $event->status = $status;

        $event->save();

        if ($event->bet->status === 'waiting_result')
            $this->resolveBet($event->bet, $status);
    }

    private function resolveBet(Bet $bet, $status)
    {
        if ($status === 'lost')
            $bet->setLostResult();

        if ($status === 'cancelled')
            $bet->cancelBet();

        if ($status === 'won' && !$bet->hasUnresolvedEvents())
            $bet->setWonResult();
    }
}