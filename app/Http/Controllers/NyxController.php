<?php

namespace App\Http\Controllers;
use App\GlobalSettings;
use App\UserBetTransaction;
use App\UserSession;
use App\UserBet;
use App\User;
use Request;
use Response;
use SimpleXMLElement;


class NyxController extends Controller {
    private $nyxuser = "nogsuser";
    private $nyxpass = "qwerty";
    private $betLimit = "100";
    private $requiredParams;
    private $responseXML;

    public function __construct() {
        $this->responseXML = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\" ?><RSP></RSP>");
        $this->requiredParams = ["apiversion","loginname","password","request"];
    }

    /**
     * Handle NYX Requests
     * @return \Illuminate\Http\Response
     */
    public function nyxWallet() {
        $request = Request::input("request");
        switch  ($request) {
            case "getaccount":
                $this->getAccount();
                break;
            case "getbalance":
                $this->getBalance();
                break;
            case "wager":
                $this->wager();
                break;
            case "result":
                $this->result();
                break;
            case "rollback":
                $this->rollback();
                break;
            case "ping":
                $this->ping();
                break;
            default:
                $this->setCode(110, "Wrong method name");
                break;
        }
        return Response::make($this->responseXML->asXML(), '200')->header('Content-Type', 'text/xml');
    }

    /**
     * Set code in responseXML attributes
     * @param $rc
     * @param $msg
     */
    private function setCode($rc, $msg = "") {
        $this->responseXML->addAttribute("rc",$rc);
        $this->responseXML->addAttribute("msg",$msg);
    }

    /**
     * Ping between ibetup and NYX
     */
    private function ping() {
        $this->responseXML->addAttribute("request", "ping");
        if (!$this->validateParams())
            $this->setCode(1008, "Missing parameter");
        elseif (!$this->validateAuth())
            $this->setCode(1003, "Authentication failed");
        else {
            $this->setCode(0, "Success");
            $this->responseXML->addChild("APIVERSION", Request::input("apiversion"));
        }
    }

    /**
     * Get user account details
     */
    private function getAccount() {
        $this->responseXML->addAttribute("request", "getaccount");
        array_push($this->requiredParams, "device", "sessionid");
        if (!$this->validateParams())
            $this->setCode(1008, "Missing parameter");
        elseif (!$this->validateAuth())
            $this->setCode(1003, "Authentication failed");
        elseif (!($user = $this->getUserBySid()))
            $this->setCode(1000, "Invalid session ID");
        elseif (!($gameSession = UserSession::logSession('new_game_session', 'game session', $user->id)))
            $this->setCode(1, "Technical error");
        else {
            $this->setCode(0, "Success");
            $this->responseXML->addChild("APIVERSION", Request::input("apiversion"));
            //TODO: Generate game sid with a model class.
            $this->responseXML->addChild("GAMESESSIONID", $gameSession->session_id);
            $this->responseXML->addChild("ACCOUNTID", $user->id);
            $this->responseXML->addChild("CURRENCY", "EUR");
            $this->responseXML->addChild("CITY", $user->profile->city);
            $this->responseXML->addChild("COUNTRY", $user->profile->country);
        }
    }

    /**
     * Get user balance
     */
    private function getBalance() {
        $this->responseXML->addAttribute("request", "getbalance");
        array_push($this->requiredParams, "accountid", "device", "gamemodel",
            "gamesessionid", "gametype", "gpgameid", "gpid", "nogsgameid",
            "product");
        if (!$this->validateParams())
            $this->setCode(1008, "Missing parameter");
        elseif (!$this->validateAuth())
            $this->setCode(1003, "Authentication failed");
        elseif (!($user = $this->getUserById()))
            $this->setCode(1000, "Invalid session ID");
        else {
            $this->responseXML->addChild("APIVERSION", Request::input("apiversion"));
            $this->responseXML->addChild("BALANCE", $user->balance->total());
            $this->setCode(0, "Success");
        }
    }

    /**
     * Check if user has balance to make the bet
     * @param $user
     * @return bool
     */
    private function validateBalance($user) {
        return (($user->balance->total()*1)>=(Request::input("betamount")*1));
    }

    /**
    * Places the bets
    */
    private function wager() {
        $info = [];
        $this->responseXML->addAttribute("request", "wager");
        array_push($this->requiredParams, "accountid", "betamount", "device",
            "gamemodel", "gamesessionid", "gametype", "gpgameid", "gpid",
            "nogsgameid", "product", "roundid", "transactionid");
        if (!$this->validateParams())
            $this->setCode(1008, "Missing parameter");
        elseif (!$this->validateAuth())
            $this->setCode(1003, "Authentication failed");
        elseif (!($user = $this->getUserById()))
            $this->setCode(1000, "Invalid session ID");
        elseif (!$this->validateBalance($user))
            $this->setCode(1006, "Out of money");
        elseif (!$this->validateWagerLimit())
            $this->setCode(1019, "Gaming limits");
        elseif ($this->validateWagerExistence($user))
            $this->setCode(0, "Duplicate wager");
        elseif (!($transaction = $this->placeBet($user, $info)))
            $this->setCode(1, "Technical error");
        else {
            $this->setCode(0, "Success");
            $this->responseXML->addChild("APIVERSION", Request::input("apiversion"));
            $this->responseXML->addChild("REALMONEYBET", Request::input("betamount"));
            //TODO: BONUS AMOUNT USED ON BET
            $this->responseXML->addChild("BONUSMONEYBET", 0);
            $this->responseXML->addChild("BALANCE", $user->balance->getTotal());
            $this->responseXML->addChild("ACCOUNTTRANSACTIONID", $info["transaction"]->id);
        }
    }

    /**
     * Reports bet results
     */
    private function result() {
        $info = [];
        $this->responseXML->addAttribute("request", "result");
        array_push($this->requiredParams, "accountid", "device", "gamemodel",
            "gamesessionid", "gamestatus", "gametype", "gpgameid", "gpid",
            "nogsgameid", "product", "result", "roundid", "transactionid");
        if (!$this->validateParams())
            $this->setCode(1008, "Missing parameter");
        elseif (!$this->validateAuth())
            $this->setCode(1003, "Authentication failed");
        elseif (!($user = $this->getUserById()))
            $this->setCode(1000, "Invalid session ID");
        elseif (!($bet = $this->getBet($user)))
            $this->setCode(102, "Wager not found");
        elseif ($bet->status === "processed")
            $this->setCode(110, "This operation is not allowed");
        elseif (false) //$this->validateResultExistence($user)
            $this->setCode(0, "Duplicate Result");
        elseif (!$this->updateBet($user, $bet, $info))
            $this->setCode(1, "Technical error");
        else {
            $this->setCode(0, "Success");
            $this->responseXML->addChild("APIVERSION", Request::input("apiversion"));
            $this->responseXML->addChild("BALANCE", $user->balance->balance_available);
            $this->responseXML->addChild("ACCOUNTTRANSACTIONID", $info["transaction"]->id);
        }
    }

    /**
     * Rollback a result or a wager
     */
    private function rollback() {
        array_push($this->requiredParams, "accountid", "device", "gamemodel",
            "gamesessionid", "gametype", "gpgameid", "gpid",
            "nogsgameid", "product", "rollbackamount", "roundid",
            "transactionid");
        if (!$this->validateParams())
            $this->setCode(1008, "Missing parameter");
        elseif (!$this->validateAuth())
            $this->setCode(1003, "Authentication failed");
        elseif (!($user = $this->getUserById()))
            $this->setCode(1000, "Invalid session ID");
        elseif (!($bet = $this->getBet($user)))
            $this->setCode(102, "Wager not found");
        elseif (false)  //$this->validateResultExistence()
            $this->setCode(110, "This operation is not allowed");
        elseif (false)  //$this->validateRollbackExistence()
            $this->setCode(0, "Duplicate rollback");
        elseif (!$this->rollbackBet()) {
            $this->setCode(1, "Technical error");
        } else {
            $this->setCode(0, "Success");
            $this->responseXML->addChild("APIVERSION", Request::input("apiversion"));
            $this->responseXML->addChild("BALANCE", $user->balance->getTotal());
            $this->responseXML->addChild("ACCOUNTTRANSACTIONID", $this->storeTransaction (
                $this->getBet($user), "deposit",
                Request::input("rollbackamount"), "rollback"
            )->id);
        }
    }

    /**
    * Validate is NYX user is valid
    * @return bool
    */
    private function validateAuth() {
        return ((Request::input("loginname") === $this->nyxuser) && (Request::input("password") === $this->nyxpass));
    }

    /**
     * Validate if the request params match the required params
     * @return bool
     */
    private function validateParams() {
        foreach ($this->requiredParams as $param)
            if (!Request::exists($param))
                return false;
        return true;
    }

    /**
     * Get user using his sessionid
     * @return \App\User
     */
    private function getUserBySid() {
        $sid = UserSession::findBySessionId(Request::input("sessionid"));
        return $sid?$sid->user:null;
    }

    /**
     * Get user by his accountid
     * @return \App\User
     */
    private function getUserById() {
        return User::findById(Request::input("accountid"));
    }

    /**
     * Validate if bet amount is inferior to operator bet limit
     * @return bool
     */
    private function validateWagerLimit() {
        return (Request::input("betamount")*1<$this->betLimit);
    }

    /**
     * Validate if wager exists
     * @param $user
     * @return boolean
     */
    private function validateWagerExistence($user) {
        return ($user->checkIfTransactionExists(Request::input("gpid")."-".Request::input("transactionid")));
    }

    /**
     * Places a bet
     * @param $user
     * @return bool
     */
    private function placeBet($user, &$info) {
        $bet = [
            'user_id' => $user->id,
            'api_bet_id' => Request::input("gpid") . "-" . Request::input("roundid"),
            'api_bet_type' => "nyx" . "-" . Request::input("product"),
            'api_transaction_id' => Request::input("gpid") . "-" . Request::input("transactionid"),
            'amount' => Request::input("betamount")*1,
            'currency' => "EUR",
            'user_session_id' => $user->getLastSession()->id,
            'status' => 'waiting_result'
        ];
        return UserBet::createNyxBet($bet, $info);
    }

    /**
     * Store bet transaction.
     * @param $userBet
     * @return mixed
     */
    private function storeTransaction($userBet, $operation, $amount, $description) {
        return (UserBetTransactions::create([
            "user_bet_id" => $userBet->id,
            "api_transaction_id" => Request::input("gpid") . "-" . Request::input("transactionid"),
            "operation" => $operation,
            "amount" => $amount,
            "description" => $description,
            "datetime" => \Carbon\Carbon::now(),
        ]));
    }

    /**
    * Get bet id from Request
    * @param $user
    * @return \App\UserBet
    */
    private function getBet($user) {
        return $user->getUserBetByBetId(Request::input("gpid")."-".Request::input("roundid"));
    }

    /**
     * Updates bet with the result
     * @param $user
     * @param $description
     * @param $info
     * @return UserBet
     */
    private function updateBet($user, $bet, &$info) {
        $bet_tax = GlobalSettings::find("bet_tax_rate")->value*1;
        $bet_result_amount =  Request::input("result")*(1-$bet_tax);
        $bet->result_amount += $bet_result_amount;
        $bet->result_tax += Request::input("result")*$bet_tax;
        $bet->result = "Won";
        $bet->status = (Request::input("gamestatus")==="pending")?"waiting_result":"processed";
        return UserBet::updateNyxBet($bet, $bet_result_amount, $info);
    }

    /**
     * Rollbacks bet
     * @param $bet
     * @param $user
     * @return \App\UserBet
     */
    private function rollbackBet($bet, $user) {
        $bet->result_amount = Request::input("rollbackamount");
        $bet->result = "Returned";
        return $user->updateBet($this->bet, Request::input("rollbackamount")*1);
    }

    /**
     * Check if result already exists
     * @param $user
     * @return boolean
     */
    private function validateResultExistence($user) {
        //TODO: user transctions must be completed first.
        return true;
    }
}