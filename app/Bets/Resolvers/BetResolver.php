<?php

namespace App\Bets\Resolvers;

use App;
use App\Bets\Bets\Bet;
use App\Bets\Bookie\BetBookie;
use App\Bets\Models\SelectionResult;
use Log;
use SportsBonus;
use App\UserBetEvent;
use DB;

class BetResolver
{
    private $events;

    private $statuses = [
//        'None' => 'none',
        'Winner' => 'won',
        'Pushed' => 'returned',
        'Loser' => 'lost',
//        'Placed' => 'placed',
        'Partial' => 'returned',
    ];

    public static function make()
    {
        return new static;
    }

    public function collect()
    {
        $this->events = UserBetEvent::past(2)
            ->with(['bet.waitingResultStatus.transaction', 'bet.userBonus', 'bet.user'])
            ->unresolved()
            ->get();

        return $this;
    }

    public function resolve()
    {
        foreach ($this->events as $event) {
            $results = $this->fetchResult($event);

            if (!$results) {
                continue;
            }
            try {
                DB::transaction(function () use ($event, $results) {
                    $this->resolveEvent($event, $results);
                });
            } catch (\Exception $e) {
                Log::error('Error resolving bet' . $event->id . ' - ' . $e->getMessage());
            }
        }
    }

    private function fetchResult(UserBetEvent $event)
    {
        return SelectionResult::find($event->api_event_id);
    }

    private function resolveEvent(UserBetEvent $event, $result)
    {
        if (!array_key_exists($result->result_status, $this->statuses)) {
            return;
        }

        $status = $this->statuses[$result->result_status];
        $event->status = $status;
        $event->save();

        if ($event->bet->status === 'waiting_result') {
            $this->resolveBet($event->bet, $status);
        }
    }

    private function resolveBet(Bet $bet, $status)
    {
        $bet->user->balance = $bet->user->balance->fresh();

        SportsBonus::swapUser($bet->user);

        if ($status === 'lost') {
            BetBookie::lostResult($bet);
        }

        if (($status === 'won' || $status === 'returned') && !$bet->hasUnresolvedEvents()) {
            if ($bet->hasReturnedEvents()) {
                if ($bet->hasWonEvents()) {
                    BetBookie::wonPartial($bet);
                } else {
                    BetBookie::returnBet($bet);
                }
            } else {
                BetBookie::wonResult($bet);
            }
        }
    }
}
