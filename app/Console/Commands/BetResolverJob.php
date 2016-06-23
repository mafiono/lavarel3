<?php

namespace app\Console\Commands;


use App\Bets\Resolvers\BetResolver;
use Illuminate\Console\Command;


class BetResolverJob extends Command
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