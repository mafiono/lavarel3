<?php

namespace App\Console\Commands;

use App\User;
use App\UserTransaction;
use Config;
use Illuminate\Console\Command;
use PayPal\Api\Payment;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class TestPaypal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:paypal {invoice} {index=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Payment on Invoice ID';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $config = Config::get('paypal');
        $mode = $config['settings']['mode'];
        $api = new ApiContext(new OAuthTokenCredential($config[$mode.'_client_id'], $config[$mode.'_secret']));
        $api->setConfig($config['settings']);

        $invoice_id = $this->argument('invoice');
        $index = $this->argument('index');

        $payments = Payment::all([
            'payee_id' => 'DNMXGUUM7XKRY',
            'count' => 40,
            'start_index' => $index,
        ], $api);
        foreach ($payments->getPayments() as $payment) {
            $trans = $payment->getTransactions();
            $thisId = $trans[0]->invoice_number;
            $tr = UserTransaction::query()->where('transaction_id', '=', $thisId)->first();
            if ($tr !== null && $tr->status_id !== 'processed' && $payment->getState() == 'approved') {
                $this->process($payment);
            } else if ($thisId === $invoice_id) {
                $this->line('Processing This one!!');
                $this->process($payment);
            }
        }
    }

    private function process($payment) {
        /** @var Payment $payment */
        $payment_id = $payment->getId();
        if ($payment->getState() != 'approved')
            return 'State is not Approved ' . $payment_id;

        $this->line('Found Payment id ' . $payment_id);

        $transactions = $payment->getTransactions();
        $amount = 0;
        $details = [];
        foreach ($transactions as $transaction) {
            $amount += $transaction->getAmount()->getTotal();
            $details['transaction'] = $transaction->toArray();
            $transId = $transaction->getInvoiceNumber();
        }
        $cost = (float)$amount * 0.035 + 0.35;

        /** @var UserTransaction $tr */
        $tr = UserTransaction::query()->where('transaction_id', '=', $transId)->first();
        if ($tr === null) {
            $this->error('Transaction not found!!');
            return ;
        }
        if ($tr->api_transaction_id === null) {
            // we can update the api_transaction_id (this can be the operation_id
            $tr->api_transaction_id = $payment_id;
            $tr->save();
        }
        $this->line('Processing Transaction id ' . $tr->id);
        /** @var User $authUser */
        $authUser = $tr->user;
        $this->line('Found User id ' . $authUser->id);

        $playerInfo = $payment->payer->getPayerInfo();
        // Create transaction
        $details['payer'] = $data = $playerInfo->toArray();
        $details = json_encode($details);

        if ($authUser->bankAccounts()->where('identity', '=', $data['payer_id'])->first() === null) {
            // create a new paypal account
            $authUser->createPayPalAccount($data);
        }

        $this->line('Updating Transaction ' . $transId);
        $authUser->updateTransaction($transId, $amount, 'processed', $tr->user_session_id, $payment_id, $details, $cost);

        return;

        /*
        dd($payments);
        $order = Order::get($invoice_id, $api);
        dd($order);

        $search = new Search();
        $search->start_invoice_date = '2017-10-31';
        $search->end_invoice_date = '2017-11-02';

        $invoices = Invoice::search($search, $api);

        dd($invoices);
        $invoice = Invoice::get($invoice_id, $api);

        dd($invoice);
        */
    }
}
