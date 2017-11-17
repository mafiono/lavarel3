<?php

namespace App\Lib\PaymentMethods\PaySafeCard;

use App\User;
use App\UserTransaction;
use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use SebastianWalker\Paysafecard\Amount;
use SebastianWalker\Paysafecard\Client;
use SebastianWalker\Paysafecard\Exceptions\PaymentError;
use SebastianWalker\Paysafecard\Urls;

class PaySafeCardApi
{
    public $environment;
    protected $logger;
    protected $api;
    protected $privateKey;

    public function __construct($configs)
    {
        $test = $configs['settings']['mode'] !== 'live';
        $this->environment = $configs['settings']['mode'] === 'live';
        $this->privateKey = $configs[$configs['settings']['mode'] . '_privateKey'];

        $this->logger = new Logger('paysafecard');
        $this->logger->pushHandler(new StreamHandler($configs['settings']['log.FileName'], Logger::DEBUG));

        $this->api = new Client($this->privateKey);
        $this->api->setTestingMode($test);
    }

    public function createCharge($amount, $userId, $metadata, $successUrl, $failureUrl, $notificationUrl)
    {
        $urls = new Urls();
        $urls->setSuccessUrl($successUrl);
        $urls->setFailureUrl($failureUrl);
        $urls->setNotificationUrl($notificationUrl);
        $this->api->setUrls($urls);
        $amount = new Amount($amount);
        $amount->setCurrency('EUR');
        $pay = new MyPayment($amount);
        $pay->createWithCustomer($this->api, [
            'id' => $userId,
            'min_age' => 18,
            'kyc_level' => 'FULL',
            'metadata' => $metadata
        ]);

        return $pay;
    }

    /**
     * @param $id
     * @throws Exception
     * @throws \SebastianWalker\Paysafecard\Exceptions\PaymentError
     */
    public function processCharge($id)
    {
        $pay = MyPayment::myFind($id, $this->api);

        if ($pay->isAuthorized()) {
            /** @var UserTransaction $tran */
            $tran = UserTransaction::query()
                ->where('api_transaction_id', '=', $pay->getId())
                ->first();
            if ($tran === null)
                throw new Exception("Payment Failed No transaction found on DB");

            /** @var User $user */
            $user = $tran->user;
            $data = $pay->getCustomerInfo();

            $ac = $user->bankAccounts()
                ->where('active', '=', '1')
                ->where('transfer_type_id', '=', 'pay_safe_card')
                ->first();
            if ($ac !== null
                && ($ac->identity !== $data->psc_id)
            ) {
                $msg = 'Não foi possível efetuar o depósito, a conta my paysafecard usada não é a que está associada a esta conta!';
                $this->logger->error('Paysafecard Fail: userId: ' . $user->id . ' Msg: '. $msg, ['customer' => $data]);
                throw new PaymentError($msg);
            }

            $pay->myCapture($this->api);

            if ($pay->isSuccessful()) {
                // Process
                if ($user->bankAccounts()
                        ->where('transfer_type_id', '=', 'pay_safe_card')
                        ->where('identity', '=', $data->psc_id)->first() === null) {
                    // create a new paypal account
                    $user->createMyPaySafeCardAccount($data);
                }

                $invoice_id = $tran->transaction_id;
                $result = $user->updateTransaction($invoice_id, $pay->getAmount()->getAmount(),
                    'processed', $tran->user_session_id, $pay->getId(),
                    $pay->getDetails(), 0);

                $this->logger->info(sprintf("Processing payment for invoice_id: %s, result %s", $invoice_id,
                    json_encode($result)));
            } else {
                throw new PaymentError("Payment Failed (" . $pay->getStatus() . ")");
            }
        } else if ($pay->isFailed()) {
            throw new PaymentError("Payment Failed (" . $pay->getStatus() . ")");
        } else {
            throw new PaymentError("Other Status (" . $pay->getStatus() . ")");
        }
    }

}