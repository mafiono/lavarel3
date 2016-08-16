<?php

namespace App\Bets\Resolvers;


use App\Bets\Bets\Bet;
use App\Bets\Bookie\BetBookie;
use App\UserBetEvent;
use DB;
use GuzzleHttp\Client;

class BetResolver
{
    private $events;

    private $statuses = [
//        'None' => 'cancelled',
        'Winner' => 'won',
        'Pushed' => 'returned',
        'Loser' => 'lost',
//        'Placed' => 'placed',
//        'Partial' => 'partial',
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
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL =>  env('ODDS_SERVER') . 'results',
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POSTFIELDS => $this->resultQuery($event),
            CURLOPT_TIMEOUT => 45
        ]);

        $ret = curl_exec($ch);

        return json_decode($ret)->results;
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
            BetBookie::lostResult($bet);

        if ($status === 'returned')
            BetBookie::returnBet($bet);

        if ($status === 'won' && !$bet->hasUnresolvedEvents())
            BetBookie::wonResult($bet);
    }
}