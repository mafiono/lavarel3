<?php

namespace App\Console\Commands;


use App\Bets\Resolvers\BetResolver;
use Illuminate\Console\Command;


class BetResolverCommand extends Command
{
    protected $signature = 'resolve-bets';

    protected $description = 'Resolves user bets';

    public function handle()
    {
        BetResolver::make()
            ->collect()
            ->resolve();

    }
}