<?php

namespace App\Http\Controllers\PaymentMethods;

use App\Http\Traits\GenericResponseTrait;
use App\Lib\PaymentMethods\SwitchPayments\SwitchApi;
use App\Models\Ad;
use App\Models\TransactionTax;
use App\User;
use App\UserTransaction;
use Config;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Session, Auth;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class SwitchPaymentsController extends Controller {

    use GenericResponseTrait;

    protected $switch_conf;
    protected $api_context;
    private $request;
    private $authUser;
    private $userSessionId;
    private $logger;

    public function __construct(Request $request) 
    {
        $this->switch_conf = Config::get('switch_payments');
        $this->api_context = new SwitchApi($this->switch_conf);

        $this->request = $request;
        $this->authUser = Auth::user();
        $this->userSessionId = Session::get('user_session');

        $this->logger = new Logger('switch_payments');
        $this->logger->pushHandler(new StreamHandler($this->switch_conf['settings']['log.FileName'], Logger::DEBUG));
    }

    /**
     * Processes a Paypal Payment
     *
     * @return JsonResponse|RedirectResponse
     */
    public function paymentPost() 
    {
        $payment_method = $this->request->get('payment_method');
        $depositValue = $this->request->get('deposit_value');
        $depositValue = str_replace(' ', '', $depositValue);
        try {
            $tax = TransactionTax::getByTransaction('cc', 'deposit');
            $taxValue = $tax->calcTax($depositValue);
        } catch (Exception $e) {
            return $this->resp('error', $e->getMessage());
        }
        if (!in_array($payment_method, ['cc', 'mc'])) {
            return $this->resp('error', 'Metodo de pagamento incorrecto!');
        }

        // TODO validar montante
        if (! $trans = $this->authUser->newDeposit($depositValue, 'cc', $taxValue)){
            return $this->resp('error', 'Ocorreu um erro, por favor tente mais tarde.');
        }
        $transId = $trans->transaction_id;
        $userId = $this->authUser->id;

        $charge = $this->api_context->createCharge(
            $amount = $depositValue + $taxValue,
            $currency = 'EUR',
            $metadata = "$userId|$transId",// something that identifies this  charge to the
            // merchant, like a userId and/or a productId
            $eventsUrl = env('SERVER_URL') .'perfil/banco/depositar/swift-pay/redirect', // Merchant Events Url, an URL
            // to the method in your server which we will HTTPS POST payment events to.
            $redirectUrl = null,
            // optional: when a payment method requires redirecting the user to a
            // different page, redirect the user back to this url afterwards
            $chargeType = 'card_onetime',
            $instrumentParams = [
                'enable3ds' => true
            ]
        );

        if (!empty($charge['id'])) {
            return $this->respType('success', 'Criado ID com sucesso', [
                'mode' => $this->api_context->environment,
                'public_key' => $this->api_context->publicKey,
                'amount' => $amount,
                'switch_id' => $charge['id'],
            ]);
        } else {
            return $this->respType('error', 'Erro ao comunicar com o portal de pagamentos, tente mais tarde por favor.');
        }
    }

    public function callbackAction(){
        $sw = $this->api_context;

        $this->api_context->handleEvent($_REQUEST['event'], [
            'payment.success' => function($event) use ($sw) {
                // use $event['payment']['id'] and
                // $event['charge']['metadata'] for reference;
                list($userId, $invoice_id) = explode('|', $event['charge']['metadata']);

                $user = User::findById($userId);
                if ($user === null) {
                    throw new Exception("Payment don't have a user ID");
                }
                $trans = UserTransaction::findByTransactionId($invoice_id);
                if ($trans === null || $trans->status_id !== 'canceled') {
                    throw new Exception("Payment is already processed!");
                }
                if ($user->id !== $trans->user_id) {
                    throw new Exception("Payment and user is incorrect!");
                }
                $amount = $event['payment']['amount'];
                $details = json_encode($event);
                // (interchange + mark-up) 0.95% + (per transaction Swift Pay) 0.09€
                $cost = (float)$amount * 0.0095 + 0.09;

                $result = $user->updateTransaction($invoice_id, $amount, 'processed', $trans->user_session_id, null, $details, $cost);
                $this->logger->info(sprintf("Processing payment for invoice_id: %s, result %s", $invoice_id, $result));
                if(Cookie::get('ad') != null)
                {
                    $ad = Ad::where('link',Cookie::get('ad'))->first();

                    $ad->deposits += 1;
                    $ad->totaldeposits += $amount;
                    $ad->save();
                }
            },
            'payment.error' => function($event) use ($sw) {
                // use $event['payment']['id'] and
                // $event['charge']['metadata'] for reference;
            }
        ]);
    }
}
