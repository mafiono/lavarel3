<?php

namespace App\Bets\Resolvers;

abstract class BetResolver
{
    protected $bets = [];

    abstract public function collectResults();

    abstract public function resolveBets();

}