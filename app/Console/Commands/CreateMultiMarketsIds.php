<?php

namespace App\Console\Commands;

use App\Lib\IdentityVerifier\PedidoVerificacaoTPType;
use App\Lib\IdentityVerifier\VerificacaoIdentidade;
use App\Models\SportsMarketsMultiple;
use App\User;
use App\UserBet;
use Exception;
use Illuminate\Console\Command;
use Log;
use Mail;
use Request;

class CreateMultiMarketsIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multi_markets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify Email';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $query = UserBet::query()
            ->where('type', '=', 'multi')
            ->whereNull('market_id');

        foreach ($query->get() as $bet) {
            $events = $bet->events;
            $markets = [];
            foreach ($events as $event) {
                $markets[] = $event->api_market_id;
            }
            $id = SportsMarketsMultiple::getId($markets);
            $bet->market_id = $id;
            $bet->save();
        }

        dd($query->count());
    }
}
