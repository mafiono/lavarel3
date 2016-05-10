<?php

namespace App\Bets\Resolvers;

abstract class BetResolver {
    protected $bets;

    private function __construct() {}

    abstract public function collectResults();
    abstract public function resolveBets();
}