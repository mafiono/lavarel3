<?php

namespace App\Console\Commands;

use App\Lib\PaymentMethods\MeoWallet\MeowalletPaymentModelProcheckout;
use Carbon\Carbon;
use Config;
use Illuminate\Console\Command;

class TestMeoWallet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:meowallet {invoice=0} {--date=} {--force} {--applyCost}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Payment on Invoice ID: test:meowallet {invoice} {--date=} {--force} {--applyCost}';

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
        $date = $this->option('date');
        $applyCost = $this->option('applyCost') ?? false;
        $status = 'COMPLETED';
        $amount = '0';
        $method = 'MB';

        $api->setForce($force);
        $api->applyCost($applyCost);

        if ($date !== null) {
            $date = Carbon::createFromFormat('Y-m-d', $date);
            if ($date->year < 2017) {
                $this->error('Chose a date from 2017');
                return;
            }
            $d = $date->format('Y-m-d');
            $page = 0;
            do {
                $ops = $this->processList($api, $d, $page++);
            } while ($ops->total > ($page * 30));
        } else {
            $api->processPayment('ABC', $invoice, $status, $amount, $method);
        }
        return 'Ok';
    }

    /**
     * @param $api
     * @param $d
     * @param $page
     */
    private function processList($api, $d, $page)
    {
        /** @var MeowalletPaymentModelProcheckout $api */
        $offset = $page * 30;
        $operations = $api->http('operations/?limit=30&offset=' . $offset . '&startdate=' . $d . '&enddate=' . $d);
        $total = $operations->total;

        $this->line('Processing from ' . $offset . '/' . $total . ' for day: ' . $d);
        foreach ($operations->elements as $op) {
            try {
                $api->processSingleOperation($op);
            } catch (\Exception $e) {
                $this->error('Error on ' . $op->id . ': ' . $e->getMessage());
            }
        }
        return $operations;
    }
}
