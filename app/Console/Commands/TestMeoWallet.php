<?php

namespace App\Console\Commands;

use App\Lib\PaymentMethods\MeoWallet\MeowalletPaymentModelProcheckout;
use Config;
use Illuminate\Console\Command;

class TestMeoWallet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:meowallet {invoice} {amount} {--force} {--applyCost}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Payment on Invoice ID: test:meowallet {invoice} {amount} {--force} {--applyCost}';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $config = Config::get('meo_wallet');
        $api = new MeowalletPaymentModelProcheckout($config);

        $invoice = $this->argument('invoice');
        $force = $this->option('force') ?? false;
        $applyCost = $this->option('applyCost') ?? false;
        $status = 'COMPLETED';
        $amount = $this->argument('amount');
        $method = 'MB';

        $api->setForce($force);
        $api->applyCost($applyCost);
        $api->processPayment('ABC', $invoice, $status, $amount, $method);
    }
}
