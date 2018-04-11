<?php

namespace App\Console\Commands;

use App\Lib\PaymentMethods\PaySafeCard\PaySafeCardApi;
use Config;
use Illuminate\Console\Command;

class TestPaysafecard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:paysafecard {invoice} {amount} {--force} {--applyCost}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Payment on Invoice ID: test:paysafecard {invoice} {amount} {--force} {--applyCost}';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $config = Config::get('paysafecard');
        $api = new PaySafeCardApi($config);

        $invoice = $this->argument('invoice');

        $api->processCharge($invoice);
    }
}
