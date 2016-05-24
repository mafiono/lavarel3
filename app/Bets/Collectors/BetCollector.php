<?php

namespace App\Bets\Collectors;

abstract class BetCollector {
    protected $bets;

    private function __construct() {}

    abstract public function collectBets();
    abstract public function processBets();
}