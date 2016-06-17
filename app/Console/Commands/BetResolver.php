<?php

namespace app\Console\Commands;

use App\Bets\Resolvers\BetgeniusResolver;
use Illuminate\Console\Command;


class BetResolver extends Command
{
    protected $signature = 'resolve-bets';

    protected $description = 'Resolves user bets';

    public function handle()
    {
        BetgeniusResolver::make()
            ->collect()
            ->resolve();
    }
}