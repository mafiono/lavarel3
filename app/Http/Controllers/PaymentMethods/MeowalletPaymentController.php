<?php

namespace App\Http\Controllers\PaymentMethods;

use App, Redirect, URL;
use App\Http\Controllers\Controller;
use App\Http\Traits\GenericResponseTrait;
use App\Lib\PaymentMethods\MeoWallet\MeowalletPaymentModelProcheckout;
use App\Models\Ad;
use App\Models\TransactionTax;
use App\UserTransaction;
use Auth;
use Config;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Session;
use Log;

class MeowalletPaymentController extends Controller
{
    use GenericResponseTrait;

    private $meowallet_conf;
    private $request;
    private $authUser;
    private $userSessionId;
    private $logger;

    public function __construct(Request $request)
    {
        $this->meowallet_conf = Config::get('meo_wallet');

        $this->request = $request;
        $this->authUser = Auth::user();
        $this->userSessionId = Session::get('user_session');

        $this->logger = new Logger('meo-wallet');
        $this->logger->pushHandler(new StreamHandler(storage_path('logs/meowallet.log'), Logger::DEBUG));
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
        $depositValue = str_replace(' ', '', $depositValue);
        $depositType = $this->request->get('payment_method');

        try {
            $tax = TransactionTax::getByTransaction($depositType, 'deposit');
            $taxValue = $tax->calcTax($depositValue);
        } catch (Exception $e) {
            return $this->resp('error', $e->getMessage());
        }

        // TODO validar montante
        if (! $trans = $this->authUser->newDeposit($depositValue, 'meo_wallet', $taxValue)){
            return $this->resp('error', 'Ocorreu um erro, por favor tente mais tarde.');
        }
        $transId = $trans->transaction_id;

        $exclude = [];
        $default_method = 'WALLET';
        //'method_cc', 'method_mc', 'method_mb', 'meo_wallet'
        if ($depositType === 'mb') {
            $exclude = ['CC']; // TODO se possivel excluir o wallet no futuro
            $default_method = 'MB';
        }
        else if ($depositType === 'meo_wallet') {
            $exclude = ['MB','CC'];
            $default_method = 'WALLET';
        }
        else {
            // CC or MC
            $exclude = ['MB']; // TODO se possivel excluir o wallet no futuro
            $default_method = 'CC';
        }

        $items = [];
        $items[] = [
            'ref'   => $transId,
            'name'  => $trans->description,
            'amount' => $depositValue,
            'descr' => $trans->description,
            'qt'    => 1
        ];
        if ($taxValue > 0) {
            $items[] = [
                'ref'   => 'tax',
                'name'  => 'Taxa de Depósito',
                'amount' => $taxValue,
                'descr' => 'Taxa de Depósito',
                'qt'    => 1
            ];
        }

        $order       = [
            // TODO add some stuff here
            'user_id' => $this->authUser->id,
            'name' => $this->authUser->profile->name,
            'email' => $this->authUser->profile->email,
            'amount' => $depositValue + $taxValue,
            'currency' => 'EUR',
            'trans_id' => $transId,
            'method' => $default_method,
            'items' => $items
        ];
        $ProCheckout = $this->_getProcheckoutModel();
        $checkout    = $ProCheckout->createCheckout($trans, $order, $exclude, $default_method,
            URL::route('perfil/banco/depositar/meowallet/success'),
            URL::route('perfil/banco/depositar/meowallet/failure'));

        $url = sprintf('%s%s%s=%s', $checkout->url_redirect,
                false === strpos($checkout->url_redirect, '?') ? '?' : '&',
                'locale',
                App::getLocale());

        return $this->respType('success',
            'Redirecionando o utilizador para o site do wallet para completar o pagamento',
            [
                'type' => 'redirect',
                'redirect' => $url,
                'top' => true,
            ]);
    }

    public function failureAction()
    {
        $this->logger->info("Meo Wallet Failure", [$this->request->all()]);

        return $this->respType('error', 'Ocorreu um erro, por favor tente mais tarde.',
            [
                'type' => 'redirect',
                'redirect' => '/perfil/banco/depositar/'
            ]);
    }

    public function successAction()
    {
        $this->logger->info("Meo Wallet Success", [$this->request->all()]);

        Session::flash('has_deposited', true);

        if(Cookie::get('ad') != null)
        {
            $ad = Ad::where('link',Cookie::get('ad'))->first();

            $ad->deposits += 1;
            $depositValue = UserTransaction::where('user_id',$this->authUser->id)->orderBy('id', 'desc')->first()->debit;
            $ad->totaldeposits += $depositValue;
            $ad->save();
        }

        return $this->respType('success', 'Depósito efetuado com sucesso!',
            [
                'type' => 'redirect',
                'redirect' => '/perfil/banco/depositar/'
            ]);
    }

    public function callbackAction()
    {
        $callback = $this->_getRequestPayload();
        $this->logger->info("Meo Wallet Action", [$this->request->all(), $callback]);

        try
        {
            $this->_getProcheckoutModel()->processCallback($callback);
            return response('true', 200);
        }
        catch (\InvalidArgumentException $e)
        {
            $this->logger->warning("MEOWallet received invalid callback. Request data: '$callback'");
            return response('true', 400);
        }
        catch (\Exception $e)
        {
            $this->logger->error('MEO Wallet error processing callback. Reason: '.$e->getMessage());
            return response('true', 500);
        }
    }
}
