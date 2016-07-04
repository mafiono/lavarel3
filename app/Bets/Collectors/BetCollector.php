<?php

namespace App\Bets\Collectors;

use Auth;
use Exception;
use Illuminate\Http\Request;

abstract class BetCollector {
    protected $bets = [];

    protected $response = ['bets' => []];

    protected $request;

    protected $user;

    protected function __construct(Request $request, $user = null)
    {
        $this->request = $request;

        $this->user = $user ?: Auth::user();

        if (!$this->user && !$this->user->check())
            throw new Exception('User not logged in');
    }

    abstract public function collect();

    abstract public function process();
}