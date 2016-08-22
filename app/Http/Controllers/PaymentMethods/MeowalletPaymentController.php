<?php

namespace App\Http\Controllers\PaymentMethods;

use App, Redirect, URL;
use App\Http\Controllers\Controller;
use App\Lib\PaymentMethods\MeoWallet\MeowalletPaymentModelProcheckout;
use Auth;
use Config;
use Illuminate\Http\Request;
use Session;
use Log;

class MeowalletPaymentController extends Controller
{
    private $meowallet_conf;
    private $request;
    private $authUser;
    private $userSessionId;

    public function __construct(Request $request)
    {
        $this->meowallet_conf = Config::get('meo_wallet');

        $this->request = $request;
        $this->authUser = Auth::user();
        $this->userSessionId = Session::get('user_session');
    }

    protected function _getRequestPayload()
    {
        return file_get_contents('php://input');
    }

    /**
     * @return MeowalletPaymentModelProcheckout
     */
    protected function _getProcheckoutModel()
    {
        return new MeowalletPaymentModelProcheckout($this->meowallet_conf);
    }

    public function redirectAction()
    {
        $depositValue = $this->request->get('deposit_value');
        // TODO validar montante
        if (! $trans = $this->authUser->newDeposit($depositValue, 'meo_wallet', $this->userSessionId)){
            return Redirect::to('/banco/erro')
                ->with('error', 'Ocorreu um erro, por favor tente mais tarde.');
        }
        $transId = $trans->transaction_id;

        $order       = [
            // TODO add some stuff here
            'name' => $this->authUser->profile->name,
            'email' => $this->authUser->profile->email,
            'amount' => $depositValue,
            'currency' => 'EUR',
            'trans_id' => $transId,
            'item' => array('ref'   => $transId,
                'name'  => $trans->description,
                'amount' => $depositValue,
                'descr' => '',
                'qt'    => 1)
        ];
        $ProCheckout = $this->_getProcheckoutModel();
        $checkout    = $ProCheckout->createCheckout($trans, $order,
            URL::route('banco/depositar/meowallet/success'),
            URL::route('banco/depositar/meowallet/failure'));

        $url = sprintf('%s%s%s=%s', $checkout->url_redirect,
                                    false === strpos($checkout->url_redirect, '?') ? '?' : '&',
                                    'locale',
                                    App::getLocale());

        return Redirect::away($url);
    }

    public function failureAction()
    {
        Log::info("Meo Wallet Failure", [$this->request]);
        return Redirect::to('/banco/erro')
            ->with('error', 'Ocorreu um erro, por favor tente mais tarde.');
    }

    public function successAction()
    {
        Log::info("Meo Wallet Success", [$this->request]);
        return Redirect::to('/banco/sucesso')
            ->with('success', 'Depósito efetuado com sucesso!');
    }

    public function callbackAction()
    {
        Log::info("Meo Wallet Action", [$this->request]);
        $callback = $this->_getRequestPayload();

        try
        {
            $this->_getProcheckoutModel()->processCallback($callback);
            return response('true', 200);
        }
        catch (\InvalidArgumentException $e)
        {
            Log::warning("MEOWallet received invalid callback. Request data: '$callback'");
            return response('true', 400);
        }
        catch (\Exception $e)
        {
            Log::error('MEO Wallet error processing callback. Reason: '.$e->getMessage());
            return response('true', 500);
        }
    }
}
