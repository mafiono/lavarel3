<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Session, View, Response, Auth, Mail, Validator, Input;
use Illuminate\Http\Request;
use Parser;
use DB;
use App\ApiRequestLog, App\User, App\UserSession;
use SimpleXMLElement;
class ApiController extends Controller
{
    protected $request;
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    private function xlog($tag, $msg) {
        DB::insert('insert into logs (tag, log) values (?, ?)', array($tag, $msg));
    }

    /**
     * Handle BetConstruct Requests
     *
     * @return void
     */
    public function handleRequests()
    {
        $dataPOST = trim(file_get_contents('php://input'));
        if (empty($dataPOST)) {
            return Response::json( ['status' => 'error', 'msg' => 'Invalid Request'], 400 );
        }
        /* Check if XML is valid */
        libxml_use_internal_errors(true);
        $doc = simplexml_load_string($dataPOST);
        if (!$doc) {
            return Response::json( ['status' => 'error', 'msg' => 'Invalid Request'], 400 );
        }
        $request = Parser::xml($dataPOST);
        ApiRequestLog::create(['request' => json_encode($request)]);
        /* Check required params */
        if (!isset($request['Method']) || !isset($request['AuthToken']) || !isset($request['TS']) || !isset($request['Hash'])) {
            return Response::json( ['status' => 'error', 'msg' => 'Invalid Request'], 400 );
        }
        $response = $request;
        /* Check if timestamp is valid */
        if ($request['Method'] != 'MoneyDeposit') {
            $diff = strtotime(date('Y-m-d H:i:s')) - $request['TS'];
            if ($diff > 20) {
                return $this->apiResponse(500, 'Request has expired', $response);
            }
        }

        $this->xlog("REQUEST",$request['Method']);

        /* Handle each method individually */
        switch ($request['Method']) {
            case 'GetAccountDetails':
                return $this->_processGetAccountDetails($request, $response);
                break;
            case 'GetBalance':
                return $this->_processGetBalance($request, $response);
                break;
            case 'Ping':
                return $this->_processPing($request, $response);
                break;
            case 'MoneyWithdraw':
                return $this->_processMoneyWithdraw($request, $response);
                break;
            case 'MoneyDeposit':
                return $this->_processMoneyDeposit($request, $response);
                break;
        }
        return Response::json( ['status' => 'error', 'msg' => 'Invalid Request'], 400 );
    }
    /**
     * Handle BetConstruct Request GetAccountDetails
     *
     * @return void
     */
    private function _processGetAccountDetails($request, $response)
    {
        if (! $user = User::findByApiPassword($request['AuthToken'])) {
            return $this->apiResponse(501, 'Invalid AuthToken', $response);
        }
        $response['Params'] = [
            'user_id' => $user->id,
            'currency' => 'eur'
        ];
        return $this->apiResponse(0, '', $response);
    }
    /**
     * Handle BetConstruct Request GetBalance
     *
     * @return void
     */
    private function _processGetBalance($request, $response)
    {
        if (! $user = User::findByApiPassword($request['AuthToken'])) {
            return $this->apiResponse(501, 'Invalid AuthToken', $response);
        }
        $response['Params'] = [
            'balance' => $user->balance->balance_available
        ];
        return $this->apiResponse(0, '', $response);
    }
    /**
     * Handle BetConstruct Request MoneyWithdraw
     *
     * @return void
     */
    private function _processMoneyWithdraw($request, $response)
    {
        /** @var $user User */
        if (! $user = User::findByApiPassword($request['AuthToken'])) {
            return $this->apiResponse(501, 'Invalid AuthToken', $response);
        }
        $valueToWithdraw = $request['Params']['amount'];
        $valueToWithdraw = $valueToWithdraw * 0.01;
        if ($user->balance->balance_available - $valueToWithdraw < 0) {
            return $this->apiResponse(603, 'Insufficient Balance', $response);
        }
        $alreadyProcessed = 1;
        if (!$user->checkIfTransactionExists($request['Params']['transaction_id'])) {
            $alreadyProcessed = 0;
            $userSession = $user->logUserSession('bet.received', 'Bet received from Betconstruct API');
            $userSessionId = $userSession->id;
            $bet = [
                'user_id' => $user->id,
                'api_bet_id' => $request['Params']['bet_id'],
                'api_bet_type' => 'betconstruct',
                'api_transaction_id' => $request['Params']['transaction_id'],
                'amount' => $valueToWithdraw,
                'currency' => $request['Params']['currency'],
                'user_session_id' => $userSessionId,
                'status' => 'waiting_result'
            ];
            if (! $user->newBet($bet)) {
                return $this->apiResponse(604, 'Operator Error', $response);
            }
        }
        $response['Params'] = [
            'balance_after' => $user->balance->balance_available,
            'already_processed' => $alreadyProcessed
        ];
        if (isset($response['AdditionalInfo'])) {
            unset($response['AdditionalInfo']);
        }
        return $this->apiResponse(0, '', $response);
    }
    /**
     * Handle BetConstruct Request MoneyDeposit
     *
     * @return void
     */
    private function _processMoneyDeposit($request, $response)
    {
        if (! $user = User::findByApiPassword($request['AuthToken'])) {
            return $this->apiResponse(501, 'Invalid AuthToken', $response);
        }
        $betId = $request['Params']['bet_id'];
        $withdrawalId = $request['Params']['withdrawal_id'];
        $transactionId = $request['Params']['transaction_id'];
        $retryMode = $request['Params']['retry_mode'];
        $userBet = $user->getUserBetByBetId($betId);
        if (!$userBet) {
            if ($retryMode == 3) {
                $response['Params'] = [
                    'balance_after' => $user->balance->balance_available,
                    'already_processed' => 0
                ];
                return $this->apiResponse(0, '', $response);
            }else{
                unset($response['Params']);
                return $this->apiResponse(701, 'Invalid Transaction Id', $response);
            }
        }
        $alreadyProcessed = 1;
        if ($userBet->api_withdrawal_id != $withdrawalId) {
            $alreadyProcessed = $valueToDeposit = 0;
            $betStatus = $request['Params']['bet_status'];
            if (in_array($retryMode, array(0, 1, 2))) {
                if ($betStatus == 1) {
                    $userBet->result = 'Lost';
                    $userBet->result_amount = 0;
                }elseif ($betStatus == 2) {
                    $userBet->result = 'Returned';
                    $userBet->result_amount = $valueToDeposit = $request['Params']['amount'];
                }elseif ($betStatus == 3) {
                    $userBet->result = 'Won';
                    $userBet->result_amount = $valueToDeposit = $request['Params']['amount'];
                }
            }elseif ($retryMode == 3) {
                $userBet->result = 'BC Deposit';
                $userBet->result_amount = $valueToDeposit = $request['Params']['amount'];
            }else{
                return $this->apiResponse(702, 'Invalid Retry Mode', $response);
            }
            $userBet->api_withdrawal_id = $withdrawalId;
            if (! $user->updateBet($userBet, $valueToDeposit)) {
                return $this->apiResponse(703, 'Operator Error', $response);
            }
        }else{
            if ($retryMode == 4) {
                $alreadyProcessed = 0;
                $userBet->api_transaction_id = $transactionId;
                if ($request['Params']['amount'] < $userBet->amount) {
                    $userBet->result = 'Bet Recalculated Less';
                }else {
                    $userBet->result = 'Bet Recalculated More';
                }
                $userBet->result_amount = $valueToDeposit = $request['Params']['amount'];
                if (! $user->updateBet($userBet, $valueToDeposit)) {
                    return $this->apiResponse(703, 'Operator Error', $response);
                }
            }
        }
        $response['Params'] = [
            'balance_after' => $user->balance->balance_available,
            'already_processed' => $alreadyProcessed
        ];
        return $this->apiResponse(0, '', $response);
    }
    /**
     * Handle BetConstruct Request Ping
     *
     * @return void
     */
    private function _processPing($request, $response)
    {
        return $this->apiResponse(0, '', $response);
    }
    private function array_to_xml($array, &$xml_user_info) {
        foreach($array as $key => $value) {
            if(is_array($value)) {
                if(!is_numeric($key)){
                    $subnode = $xml_user_info->addChild("$key");
                    $this->array_to_xml($value, $subnode);
                }else{
                    $subnode = $xml_user_info->addChild("item$key");
                    $this->array_to_xml($value, $subnode);
                }
            }else {
                $xml_user_info->addChild("$key",htmlspecialchars("$value"));
            }
        }
    }
    private function array2xml($data) {
        //creating object of SimpleXMLElement
        $xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><Root></Root>");
        //function call to convert array to xml
        $this->array_to_xml($data, $xml_user_info);
        return $xml_user_info->asXml();
    }
    private function apiResponse($errorCode, $errorMsg, $response)
    {
        $response['TS'] = strtotime(date('Y-m-d H:i:s'));
        $response['ErrorCode'] = $errorCode;
        $response['ErrorText'] = $errorMsg;
        $hashCode = '';
        foreach ($response as $key => $item) {
            if (!is_array($item) && $key != 'Hash' && $key != 'Params') {
                $hashCode .= $key.$item;
            }
        }
        if (!empty($response['Params'])) {
            foreach ($response['Params'] as $key => $value) {
                $hashCode .= $key.$value;
            }
        }
        $sharedKey = 'i7S4fKdEudNtudQbHhIP5cuzwonDy6pp';
        $response['Hash'] = md5($hashCode.$sharedKey);
        ApiRequestLog::create(['request' => json_encode($response)]);
        return Response::make( $this->array2xml($response) );
    }
}
