<?php

namespace App\Bets\Bets;


use Exception;

class BetException extends Exception
{
    private $eventId;

    public function __construct($msg, $eventId = null)
    {
        $this->eventId = $eventId;

        parent::__construct($msg, 0, null);
    }

    public function getEventId()
    {
        return $this->eventId;
    }
}