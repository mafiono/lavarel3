<?php

namespace App\Lib\PaymentMethods\PaySafeCard;

use App\User;
use App\UserBankAccount;
use App\UserTransaction;
use Monolog\Handler\StreamHandler;
use App\Http\Traits\GenericResponseTrait;
use App\Lib\PaymentMethods\PaySafeCard\PaymentError;
use Monolog\Logger;
use SebastianWalker\Paysafecard\Amount;
use SebastianWalker\Paysafecard\Client;
use SebastianWalker\Paysafecard\Urls;

class PaySafeCardApi
{
    use GenericResponseTrait;
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

        $this->api = new MyClient($this->privateKey);
        $this->api->setTestingMode($test);

        MyPayment::$logger = $this->logger;
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
     * Process Change
     * @param $id
     * @throws PaymentError
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
                throw new PaymentError(trans('paysafecard.api.errorDB'));

            /** @var User $user */
            $user = $tran->user;
            $data = $pay->getCustomerInfo();

            $ac = $user->bankAccounts()
                ->where('active', '=', '1')
                ->where('transfer_type_id', '=', 'pay_safe_card')
                ->first();
            if ($ac !== null
                && ((String) $ac->identity !== (String)$data->psc_id)
            ) {
                $msg = trans('paysafecard.api.unique_account');
                $this->logger->error('Paysafecard Fail: userId: ' . $user->id . ' Msg: '. $msg, ['customer' => $data]);
                throw new PaymentError($msg);
            }

            $pay->myCapture($this->api);

            if ($pay->isSuccessful()) {
                // Process
                if ($user->bankAccounts()
                        ->where('transfer_type_id', '=', 'pay_safe_card')
                        ->where('identity', '=', $data->psc_id)
                        ->first() === null) {
                    // create a new Paysafecard account
                    $user->createMyPaySafeCardAccount($data);
                }

                $invoice_id = $tran->transaction_id;
                $result = $user->updateTransaction($invoice_id, $pay->getAmount()->getAmount(),
                    'processed', $tran->user_session_id, $pay->getId(),
                    $pay->getDetails(), 0);

                $this->logger->info(sprintf("Processing payment for invoice_id: %s, result %s", $invoice_id,
                    json_encode($result)));
            } else {
                $tran = UserTransaction::query()
                    ->where('api_transaction_id', '=', $pay->getId())
                    ->first();
                if ($tran->status_id === 'processed') {
                    throw new PaymentError(trans('paysafecard.api.success'));
                }
                $this->logger->error('Unknow Status: ' . $pay->getStatus(), ['id' => $id, 'pay' => $pay]);
                throw new PaymentError(trans('paysafecard.api.error'));
            }
        } else if ($pay->isFailed()) {
            if ($pay->getStatus() === 'CANCELED_CUSTOMER')
                throw new PaymentError(trans('paysafecard.api.abort'));

            $this->logger->error('Unknow Status: ' . $pay->getStatus(), ['id' => $id, 'pay' => $pay]);
            throw new PaymentError(trans('paysafecard.api.error'));
        } else {
            if ($pay->getStatus() === 'SUCCESS')
                return;

            $this->logger->error('Not Authorized Status: ' . $pay->getStatus(), ['id' => $id, 'pay' => $pay]);
            $tran = UserTransaction::query()
                ->where('api_transaction_id', '=', $pay->getId())
                ->first();
            if($tran->status_id !== 'processed'){
                $this->logger->error('Unknow Status: ' . $pay->getStatus(), ['id' => $id, 'pay' => $pay]);
                throw new PaymentError(trans('paysafecard.api.error'));
            }
        }
    }

    public function validateAccount($account, $email, $amount)
    {
        /** @var UserBankAccount $account */
        $attr = json_decode($account->account_details, true);
        if ($email !== null) {
            $attr['email'] = $email;
        }

        $valid = $this->processPayout($attr, $amount);
        if ($valid) {
            $account->bank_account = $email;
            $account->account_details = json_encode($attr);
            $account->account_ready = true;
            $account->save();
        }
        return $valid;
    }

    public function processPayout($attr, $amount)
    {
        $out = array_only($attr, ['id', 'email', 'first_name', 'last_name', 'date_of_birth']);
        $data = [
            'type' => 'PAYSAFECARD',
            'capture' => false,
            'amount' => $amount,
            'currency' => 'EUR',
            'customer' => $out
        ];

        $result = $this->api->sendRequest('post', 'payouts', $data);
        $this->logger->error('Payout Result:', ['result' => $result, 'data' => $data]);
        if ($result->status !== 'VALIDATION_SUCCESSFUL') {
            throw new PaymentError(trans('paysafecard.api.validation'));
        }
        app()->instance('pay_safe_card.payout', $result);

        return true;
    }
}