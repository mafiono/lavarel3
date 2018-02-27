<?php

namespace App\Http\Controllers\PaymentMethods;

use App\Http\Traits\GenericResponseTrait;
use App\Lib\PaymentMethods\PaySafeCard\PaySafeCardApi;
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

class PaysafecardController extends Controller {

    use GenericResponseTrait;

    protected $psc_conf;
    protected $api_context;
    private $request;
    private $authUser;
    private $userSessionId;
    private $logger;

    public function __construct(Request $request) 
    {
        $this->psc_conf = Config::get('paysafecard');
        $this->api_context = new PaySafeCardApi($this->psc_conf);

        $this->request = $request;
        $this->authUser = Auth::user();
        $this->userSessionId = Session::get('user_session');

        $this->logger = new Logger('paysafecard');
        $this->logger->pushHandler(new StreamHandler($this->psc_conf['settings']['log.FileName'], Logger::DEBUG));
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
            $tax = TransactionTax::getByTransaction('pay_safe_card', 'deposit');
            $taxValue = $tax->calcTax($depositValue);
        } catch (Exception $e) {
            return $this->resp('error', $e->getMessage());
        }
        if ($payment_method !== 'pay_safe_card') {
            return $this->resp('error', trans('paysafecard.controller.wrong_method'));
        }

        // TODO validar montante
        if (! $trans = $this->authUser->newDeposit($depositValue, 'pay_safe_card', $taxValue)){
            return $this->resp('error', trans('paysafecard.controller.try_later'));
        }
        $transId = $trans->transaction_id;
        $userId = $this->authUser->id;

        try {
            $charge = $this->api_context->createCharge(
                $amount = $depositValue + $taxValue,
                $userId,
                $metadata = "$userId|$transId",// something that identifies this  charge to the
                // merchant, like a userId and/or a productId
                $successUrl = env('SERVER_URL') .'perfil/banco/depositar/paysafecard/success/{payment_id}', // Merchant Events Url, an URL
                $failureUrl = env('SERVER_URL') .'perfil/banco/depositar/paysafecard/failure/{payment_id}', // Merchant Events Url, an URL
                $notificationUrl = env('SERVER_URL') .'perfil/banco/depositar/paysafecard/redirect', // Merchant Events Url, an URL
                // to the method in your server which we will HTTPS POST payment events to.
                $redirectUrl = null
            );

            if ($charge !== null && $charge->getStatus() === 'INITIATED' && $this->save($trans, $charge->getId())) {
                return $this->respType('success', trans('paysafecard.controller.id_success'), [
                    'amount' => $amount,
                    'psc' => $charge->getStatus(),
                    'auth_url' => $charge->getAuthUrl().'&locale=pt_PT',
                ]);
            }
        } catch (Exception $e) {
            $this->logger->error('Error Creating Change: ' . $e->getMessage());
        }

        // Transaction could not be initiated due to connection problems. If the problem persists, please contact our support.
        return $this->respType('error', trans('paysafecard.controller.error_portal'));
    }

//    public function tryThis() {
//        $sw = $this->api_context;
//        $sw->processPayout();
//    }

    public function callbackAction()
    {
        try {
            $sw = $this->api_context;
            $id = $this->request->get('mtid');
            $sw->processCharge($id);
            return 'Ok';
        } catch (\Exception $e) {
            return response('Error', 400);
        }
    }

    public function success($id)
    {
        try {
            $sw = $this->api_context;
            $sw->processCharge($id);

            return '<script>
                top.$.fn.popup({
                    type: \'success\',
                    text: \''.trans('paysafecard.controller.success_dep').'\'
                });
                top.page(\'/perfil/banco/saldo\');
            </script>';
        } catch (\Exception $e) {
            return '<script>
                top.$.fn.popup({
                    type: \'error\',
                    text: \''.$e->getMessage().'\'
                });
                top.page(\'/perfil/banco/saldo\');
            </script>';
        }
    }

    public function failure($id)
    {
        try {
            $sw = $this->api_context;
            $sw->processCharge($id);

            return '<script>
                top.$.fn.popup({
                    type: \'success\',
                    text: \''.trans('paysafecard.controller.success_dep').'\'
                });
                top.page(\'/perfil/banco/saldo\');
            </script>';
        } catch (\Exception $e) {
            return '<script>
                top.$.fn.popup({
                    type: \'error\',
                    text: \''.$e->getMessage().'\'
                });
                top.page(\'/perfil/banco/saldo\');
            </script>';
        }
    }

    private function save($trans, $getId)
    {
        $trans->api_transaction_id = $getId;
        return $trans->save();
    }
}
