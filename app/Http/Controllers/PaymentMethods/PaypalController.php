<?php

namespace App\Http\Controllers\PaymentMethods;

use App\Http\Traits\GenericResponseTrait;
use App\Models\Ad;
use App\Models\TransactionTax;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;
use Log;
use Config, URL, Session, Auth;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class PaypalController extends Controller
{
    use GenericResponseTrait;

    private $_api_context;

    private $request;
    private $authUser;
    private $userSessionId;
    private $logger;

    public function __construct(Request $request)
    {
        // setup PayPal api context
        $paypal_conf = Config::get('paypal');
        $mode = $paypal_conf['settings']['mode'];
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf[$mode.'_client_id'], $paypal_conf[$mode.'_secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
        $this->request = $request;
        $this->authUser = Auth::user();
        $this->userSessionId = Session::get('user_session');

        $this->logger = new Logger('paypal');
        $this->logger->pushHandler(new StreamHandler($paypal_conf['settings']['log.FileName'], Logger::DEBUG));
    }

    private static function clean_name($var)
    {
        $text = strtolower($var);
        $text = iconv('utf-8', 'ascii//TRANSLIT', $text);
        $text = trim($text);
        return $text;
    }

    /**
     * Processes a Paypal Payment
     *
     * @return JsonResponse|RedirectResponse
     */
    public function paymentPost()
    {
        $depositValue = $this->request->get('deposit_value');
        $depositValue = str_replace(' ', '', $depositValue);
        try {
            $tax = TransactionTax::getByTransaction('paypal', 'deposit');
            $taxValue = $tax !== null ? $tax->calcTax($depositValue) : 0;
        } catch (Exception $e) {
            return $this->resp('error', $e->getMessage());
        }

        // TODO validar montante
        if (!$trans = $this->authUser->newDeposit($depositValue, 'paypal', $taxValue)) {
            return $this->resp('error', 'Ocorreu um erro, por favor tente mais tarde.');
        }
        $transId = $trans->transaction_id;

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $items = [];
        $item_1 = new Item();
        $item_1->setName($trans->description)// item name
        ->setCurrency('EUR')
            ->setQuantity(1)
            ->setPrice($depositValue);
        $items[] = $item_1;

        if ($taxValue > 0) {
            $item_2 = new Item();
            $item_2->setName('Taxa de Depósito')// item name
            ->setCurrency('EUR')
                ->setQuantity(1)
                ->setPrice($taxValue);
            $items[] = $item_2;
        }
        // add item to list
        $item_list = new ItemList();
        $item_list->setItems($items);

        $amount = new Amount();
        $amount->setCurrency('EUR')
            ->setTotal($depositValue + $taxValue);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setInvoiceNumber($transId)
            ->setCustom($transId)
            ->setDescription('Depósito ...');

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('perfil/banco/depositar/paypal/status'))// Specify return URL
        ->setCancelUrl(URL::route('perfil/banco/depositar/paypal/status'));

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            if (\Config::get('app.debug')) {
                echo "Exception: " . $ex->getMessage() . PHP_EOL;
                $err_data = json_decode($ex->getData(), true);
                $this->logger->error('Paypal Fail: userId: ' . $this->authUser->id . ' Msg: '. $ex->getMessage(), ['$err_data' => $err_data]);
                return $this->resp('error', 'Ocorreu um erro, por favor tente mais tarde.');
            }
            return $this->resp('error', 'Ocorreu um erro, por favor tente mais tarde.');
        }

        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        $trans->api_transaction_id = $payment->getId();
        $trans->save();

        // add payment ID to session
        Session::put('paypal_payment_id', $payment->getId());

        if (isset($redirect_url)) {
            // redirect to paypal
            // Old way
            // return Redirect::away($redirect_url);
            return $this->respType('success',
                'Redirecionando o utilizador para o site do paypal para completar o pagamento',
                [
                    'type' => 'redirect',
                    'redirect' => $redirect_url,
                    'top' => true,
                ]);
        }

        return $this->resp('error', 'Ocorreu um erro, por favor tente mais tarde.');
    }

    /**
     * Processes a Paypal Response
     *
     * @return JsonResponse|RedirectResponse
     */
    public function paymentStatus()
    {
        try {

            // Get the payment ID before session clear
            $payment_id = Session::get('paypal_payment_id'); // ?: $this->request->get('paymentId');

            // clear the session payment ID
            Session::forget('paypal_payment_id');

            if (empty($this->request->get('PayerID')) || empty($this->request->get('token')))
                return $this->respType('error', 'O depósito foi cancelado',
                    [
                        'type' => 'redirect',
                        'redirect' => '/perfil/banco/depositar/'
                    ]);

            $payment = Payment::get($payment_id, $this->_api_context);
            $trans = $payment->getTransactions();
            $transId = '';
            foreach ($trans as $tr) {
                /* @var $tr  \PayPal\Api\Transaction */
                $transId = $tr->getInvoiceNumber();
            }
            $playerInfo = $payment->payer->getPayerInfo();
            $ac = $this->authUser->bankAccounts()
                ->where('active', '=', '1')
                ->where('transfer_type_id', '=', 'paypal')
                ->first();
            if ($ac !== null
                && ($ac->bank_account !== $playerInfo->email
                    || $ac->identity !== $playerInfo->payer_id)
            ) {
                $msg = 'Não foi possível efetuar o depósito, a conta paypal usada não é a que está associada a esta conta!';
                $this->logger->error('Paypal Fail: userId: ' . $this->authUser->id . ' Msg: '. $msg, ['playerInfo' => $playerInfo->toArray()]);
                return $this->respType('error', $msg,
                    [
                        'type' => 'redirect',
                        'redirect' => '/perfil/banco/depositar/'
                    ]);
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
                $details = [];
                foreach ($transactions as $transaction) {
                    $amount += (float)$transaction->getAmount()->getTotal();
                    $details['transaction'] = $transaction->toArray();
                }
                $cost = (float)$amount * 0.035 + 0.35;

                // Create transaction
                $details['payer'] = $data = $playerInfo->toArray();
                $details = json_encode($details);

                if ($this->authUser->bankAccounts()
                        ->where('transfer_type_id', '=', 'paypal')
                        ->where('identity', '=', $data['payer_id'])->first() === null) {
                    // create a new paypal account
                    $this->authUser->createPayPalAccount($data);
                }

                $this->authUser->updateTransaction($transId, $amount, 'processed', $this->userSessionId, $payment_id, $details, $cost);

                Session::flash('has_deposited', true);

                if (Cookie::get('ad') != null) {
                    $ad = Ad::where('link', Cookie::get('ad'))->first();

                    $ad->deposits += 1;
                    $ad->totaldeposits += $amount;
                    $ad->save();
                }

                return $this->respType('success', 'Depósito efetuado com sucesso!',
                    [
                        'type' => 'redirect',
                        'redirect' => '/perfil/banco/depositar/'
                    ]);
            }
            $this->logger->error('Paypal Fail: userId: ' . $this->authUser->id . ' Msg: Invalid State '. $result->getState(),
                ['result' => $result->toArray()]);

            return $this->respType('error', 'Não foi possível efetuar o depósito, por favor tente mais tarde',
                [
                    'type' => 'redirect',
                    'redirect' => '/perfil/banco/depositar/'
                ]);
        } catch (Exception $e) {
            $this->logger->error('Paypal Fail: userId: ' . $this->authUser->id . ' Msg: '. $e->getMessage(), ['error' => $e->getTraceAsString()]);
            return $this->respType('error', 'Não foi possível efetuar o depósito, por favor tente mais tarde',
                [
                    'type' => 'redirect',
                    'redirect' => '/perfil/banco/depositar/'
                ]);
        }
    }
}
