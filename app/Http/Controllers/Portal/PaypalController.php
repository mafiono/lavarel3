<?php

namespace App\Http\Controllers\Portal;

use App\UserTransaction;
use Config, URL, Session, Redirect, Auth;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use PayPal\Api\Transactions;
use App\Movimento;

class PaypalController extends Controller {

    private $_api_context;

    private $request;
    private $authUser;
    private $userSessionId;

    public function __construct(Request $request) 
    {
        // setup PayPal api context
        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
        $this->request = $request;
        $this->authUser = Auth::user();
        $this->userSessionId = Session::get('userSessionId');        
    }

    /**
     * Processes a Paypal Payment
     *
     * @return \View
     */
    public function paymentPost() 
    {
        $depositValue = $this->request->get('deposit_value');
        // TODO validar montante
        if (! $trans = $this->authUser->newDeposit($depositValue, 'paypal', $this->userSessionId)){
            return Redirect::route('banco.depositar')
                ->with('error', 'Ocorreu um erro, por favor tente mais tarde.');
        }
        $transId = $trans->transaction_id;

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item_1 = new Item();
        $item_1->setName('CASINO') // item name
                ->setCurrency('EUR')
                ->setQuantity(1)
                ->setPrice($this->request->get('deposit_value'));

        // add item to list
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $amount = new Amount();
        $amount->setCurrency('EUR')
                ->setTotal($depositValue);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
                ->setItemList($item_list)
                ->setInvoiceNumber($transId)
                ->setCustom($transId)
                ->setDescription('Depósito ...');

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('banco/depositar/paypal/status')) // Specify return URL
                ->setCancelUrl(URL::route('banco/depositar/paypal/status'));

        $payment = new Payment();
        $payment->setIntent('sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));

        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
                echo "Exception: " . $ex->getMessage() . PHP_EOL;
                $err_data = json_decode($ex->getData(), true);
                return Redirect::route('banco.depositar')
                                ->with('error', 'Ocorreu um erro, por favor tente mais tarde.');                
            } else {
                return Redirect::route('banco.depositar')
                                ->with('error', 'Ocorreu um erro, por favor tente mais tarde.');
            }
        }

        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        // add payment ID to session
        Session::put('paypal_payment_id', $payment->getId());

        if (isset($redirect_url)) {
            // redirect to paypal
            return Redirect::away($redirect_url);
        }

        return Redirect::route('banco.depositar')
                        ->with('error', 'Ocorreu um erro, por favor tente mais tarde.');
    }

    /**
     * Processes a Paypal Response
     *
     * @return \View
     */
    public function paymentStatus() {
        // Get the payment ID before session clear
        $payment_id = Session::get('paypal_payment_id');

        // clear the session payment ID
        Session::forget('paypal_payment_id');

        if (empty($this->request->get('PayerID')) || empty($this->request->get('token')))
            return Redirect::to('/banco/depositar/')->with('error', 'O depósito foi cancelado');

        $payment = Payment::get($payment_id, $this->_api_context);
        $trans = $payment->getTransactions();
        $transId = '';
        foreach ($trans as $tr) {
            /* @var $tr  \PayPal\Api\Transaction */
            $transId = $tr->getInvoiceNumber();
        }

        // PaymentExecution object includes information necessary 
        // to execute a PayPal account payment. 
        // The payer_id is added to the request query parameters
        // when the user is redirected from paypal back to your site
        $execution = new PaymentExecution();
        $execution->setPayerId($this->request->get('PayerID'));

        //Execute the payment
        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved') {

            $transactions = $result->getTransactions();
            $amount = 0;
            foreach ($transactions as $transaction) {
                $amount += $transaction->getAmount()->getTotal();
            }
            // Create transaction
            $this->authUser->updateTransaction($transId, $amount, 'processed', $this->userSessionId, $payment_id);

            return Redirect::to('/banco/saldo/')->with('success', 'Depósito efetuado com sucesso!');
        }

        return Redirect::to('/banco/depositar')->with('error', 'Não foi possível efetuar o depósito, por favor tente mais tarde');
    }

}
