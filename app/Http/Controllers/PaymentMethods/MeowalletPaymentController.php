<?php

namespace App\Http\Controllers\PaymentMethods;

use App, Redirect, URL;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Traits\GenericResponseTrait;
use App\Lib\PaymentMethods\MeoWallet\MeowalletPaymentModelProcheckout;
use App\Models\Ad;
use App\Models\TransactionTax;
use App\Models\UserDepositReference;
use App\UserTransaction;
use Auth;
use Cache;
use Carbon\Carbon;
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
        $depositType = $this->request->get('payment_method');
        $depositType = str_replace('meowallet_', '', $depositType);
        if ($depositType === 'mb') {
            return $this->processUniqueRefMb();
        }
        $depositValue = $this->request->get('deposit_value');
        $depositValue = str_replace(' ', '', $depositValue);

        try {
            $tax = TransactionTax::getByTransaction($depositType, 'deposit');
            $taxValue = $tax->calcTax($depositValue);
        } catch (Exception $e) {
            return $this->resp('error', $e->getMessage());
        }
        if ($depositType === 'mc') {
            $depositType = 'cc';
        }
        // TODO validar montante
        if (! $trans = $this->authUser->newDeposit($depositValue, $depositType, $taxValue)){
            return $this->resp('error', 'Ocorreu um erro, por favor tente mais tarde.');
        }
        $transId = $trans->transaction_id;

        if ($depositType === 'cc') {
            return $this->processMeoWallet($transId, $trans, $depositValue, $taxValue, 'CC');
        }
        if ($depositType === 'mb') {
            return $this->processRefMb($transId, $trans, $depositValue, $taxValue);
        }
        if ($depositType === 'meo_wallet') {
            return $this->processMeoWallet($transId, $trans, $depositValue, $taxValue, 'WALLET');
        }
        throw new CustomException('error', 'Invalid Method!!');
    }

    public function failureAction()
    {
        Cache::put('use_meowallet_' . $this->authUser->id, false, 5);
        $this->logger->info("Meo Wallet Failure", [$this->request->all()]);

        return $this->respType('error', 'Ocorreu um erro, por favor tente novamente.',
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

    private function processRefMb($transId, $trans, $depositValue, $taxValue)
    {
        /** @var UserTransaction $trans */
        $ProCheckout = $this->_getProcheckoutModel();
        $data = [
            'amount' => $depositValue + $taxValue,
            'currency' => 'EUR',
            'ext_invoiceid' => $transId,
//            'ext_customerid' => $this->authUser->id,
//            'expires' => Carbon::now()->addWeek(2)->format('Y-m-d\TH:i:s') . '+0000',
        ];

        $output = $ProCheckout->createMb($trans, $data);
        return $this->respType('success',
            'Referencias geradas com successo',
            [
                'mb' => $output->mb,
                'amount' => $output->amount,
                'expires' => Carbon::now()->addWeek(2)->format('Y-m-d')
            ]);
    }


    private function processUniqueRefMb()
    {
        $hasUniqueRef = $this->getUniqueRef();
        if ($hasUniqueRef !== null) {
            return $this->respType('success',
                'Referencias geradas com successo',
                [
                    'mb' => [
                        'ref' => $hasUniqueRef->ref,
                        'entity' => $hasUniqueRef->entity,
                    ],
                ]);
        }
        $ProCheckout = $this->_getProcheckoutModel();
        $data = [
            'min_amount' => 1,
            'max_amount' => 10000,
            'currency' => 'EUR',
            'op_type' => 'PAYMENT', // valores possíveis: 'PAYMENT' ou 'ADDFUNDS'
            'ext_invoiceid' => $this->authUser->id . '_' . $this->getNextUniqueVersion(),
            'ext_email' => $this->authUser->profile->email,
            'ext_customerid' => $this->authUser->id,
//            'expires' => Carbon::now()->addWeek(2)->format('Y-m-d\TH:i:s') . '+0000',
        ];

        $output = $ProCheckout->createUniqueMbRef($data);
        $this->saveNewUniqueRef($output);

        return $this->respType('success',
            'Referencias geradas com successo',
            [
                'mb' => $output->mb,
            ]);
    }

    /**
     * @param $transId
     * @param $trans
     * @param $depositValue
     * @param $taxValue
     * @param string $method
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    protected function processMeoWallet($transId, $trans, $depositValue, $taxValue, $method = 'WALLET')
    {
        $exclude = ['MB'];
        if ($method !== 'CC') {
            $exclude[] = 'CC';
        }

        $items = [];
        $items[] = [
            'ref' => $transId,
            'name' => $trans->description,
            'amount' => $depositValue,
            'descr' => $trans->description,
            'qt' => 1
        ];
        if ($taxValue > 0) {
            $items[] = [
                'ref' => 'tax',
                'name' => 'Taxa de Depósito',
                'amount' => $taxValue,
                'descr' => 'Taxa de Depósito',
                'qt' => 1
            ];
        }

        $order = [
            'client' => [
                'address' => [
                    'address' => $this->authUser->profile->address,
                    'city' => $this->authUser->profile->city,
                    'country' => $this->authUser->profile->country,
                    'postalcode' => $this->authUser->profile->zip_code,
                ],
                'email' =>  $this->authUser->profile->email,
                'name' => $this->authUser->profile->name,
                'nif' =>  $this->authUser->profile->tax_number,
                'phone' =>  $this->authUser->profile->phone,
            ],
            'user_id' => $this->authUser->id,
            'amount' => $depositValue + $taxValue,
            'currency' => 'EUR',
            'trans_id' => $transId,
            'method' => $method,
            'items' => $items
        ];
        $ProCheckout = $this->_getProcheckoutModel();
        $checkout = $ProCheckout->createCheckout($trans, $order, $exclude, $method,
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

    private function getUniqueRef()
    {
        return UserDepositReference::query()
            ->where('user_id', '=', $this->authUser->id)
            ->where('origin', '=', 'mb')
            ->where('active', '=', 1)
            ->first();
    }

    private function getNextUniqueVersion()
    {
        return (UserDepositReference::query()
                ->where('user_id', '=', $this->authUser->id)
                ->where('origin', '=', 'mb')
                ->max('version') ?? 0) + 1;
    }

    private function saveNewUniqueRef($output)
    {
        $nextVersion = $this->getNextUniqueVersion();
        $userSession = $this->authUser->logUserSession('create.unique_mb', 'Create Unique MB Reference');

        $userRefs = new UserDepositReference();
        $userRefs->user_id = $this->authUser->id;
        $userRefs->origin = 'mb';
        $userRefs->version = $nextVersion;
        $userRefs->user_session_id = $userSession->id;
        $userRefs->ref = $output->mb->ref;
        $userRefs->entity = $output->mb->entity;
        $userRefs->active = 1;

        $userRefs->save();
    }
}
