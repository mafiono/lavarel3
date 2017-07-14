<?php

namespace App\Bets\Collectors;

use Auth;
use Exception;
use Illuminate\Http\Request;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

abstract class BetCollector {
    protected $bets = [];

    protected $response = ['bets' => []];

    protected $request;

    protected $user;

    protected $logger;

    protected function __construct(Request $request, $user = null)
    {
        $this->request = $request;

        $this->user = $user ?: Auth::user();

        if (!$this->user && !$this->user->check())
            throw new Exception('User not logged in');

        $this->logger = new Logger('bet_slip');
        $this->logger->pushHandler(new StreamHandler(storage_path('logs/bet_slip.log'), Logger::DEBUG));
    }

    abstract public function collect();

    abstract public function process();
}