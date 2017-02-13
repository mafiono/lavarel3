<?php

namespace App\Http\Controllers\PaymentMethods;

use App\Http\Traits\GenericResponseTrait;
use App\Lib\PaymentMethods\SwitchPayments\SwitchApi;
use App\Models\TransactionTax;
use Config;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Session, Auth;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class SwiftPaymentsController extends Controller {

    use GenericResponseTrait;

    protected $switch_conf;
    protected $api_context;
    private $request;
    private $authUser;
    private $userSessionId;

    public function __construct(Request $request) 
    {
        $this->switch_conf = Config::get('switch_payments');
        $this->api_context = new SwitchApi($this->switch_conf);

        $this->request = $request;
        $this->authUser = Auth::user();
        $this->userSessionId = Session::get('user_session');
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
            $metadata = "{'user':$userId, 'trans': $transId}",// something that identifies this  charge to the
            // merchant, like a userId and/or a productId
            $eventsUrl = 'https://www.casinoportugal.pt/perfil/banco/depositar/swift-pay/redirect', // Merchant Events Url, an URL
            // to the method in your server which we will HTTPS POST payment events to.
            $redirectUrl = null,
            // optional: when a payment method requires redirecting the user to a
            // different page, redirect the user back to this url afterwards
            $chargeType = 'card_onetime'
        );

        return $this->respType('success', 'Criado ID com sucesso', [
            'mode' => $this->api_context->environment,
            'token' => $this->switch_conf['publicKey'],
            'amount' => $amount,
            'switch_id' => $charge['id'],
        ]);
    }

    public function callbackAction(){

    }
}
