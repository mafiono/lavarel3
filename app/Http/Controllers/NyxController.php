<?php

namespace App\Http\Controllers;
use App\UserSession;
use App\User;

//use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
//use Session, View, Response, Auth, Mail, Validator, Input;
//use Parser;
//use DB;
//use App\ApiRequestLog, App\User, App\UserSession;
use Request;
use Response;
use SimpleXMLElement;
use SoapBox\Formatter\Formatter;

class NyxController extends Controller {
    private $loginname = "nogsuser";
    private $password = "qwerty";
    private $requiredParams;
    private $response;
    private $rc = 0;
    private $msg = "Sucess";
    private $sid = "12345";
    private $bet;
    private $user;


    public function __construct() {
        $this->response = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\" ?><RSP></RSP>");
        $this->requiredParams = ["apiversion","loginname","password","request"];
    }

    public function nyxWallet() {
        $this->validateAuthentication();
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
                break;
            case "ping":
                $this->ping();
                break;
            default:
                $this->setError(110, "Wrong method name");
                break;
        }

        $this->response->addAttribute("rc",$this->rc);
        $this->response->addAttribute("msg",$this->msg);
        return Response::make($this->response->asXML(), '200')->header('Content-Type', 'text/xml');
    }

    private function setError($rc, $msg) {
        $this->rc = $rc;
        $this->msg = $msg;

    }

    private function ping() {
        $this->validateRequiredParams();
        $this->response->addAttribute("request", "ping");
        if (!$this->rc) {
            $this->response->addChild("APIVERSION",Request::input("apiversion"));
        }
    }

    private function getAccount() {
        array_push($this->requiredParams, "device", "sessionid");
        $this->validateRequiredParams();
        $sid = UserSession::findBySessionId(Request::input("sessionid"));
        if ($sid)
            $this->user = $sid->user;
        $this->validateLogin();
        $this->response->addAttribute("request", "getaccount");
        if (!$this->rc) {
            $this->response->addChild("APIVERSION", Request::input("apiversion"));
            $this->response->addChild("GAMESESSIONID", "AAD8EE30-8C43-11DC9755-668156D89593");
            $this->response->addChild("ACCOUNTID", $this->user->id);
            $this->response->addChild("CURRENCY", "EUR");
            $this->response->addChild("CITY", $this->user->profile->city);
            $this->response->addChild("COUNTRY", $this->user->profile->country);
        }
    }

    private function getBalance() {
        array_push($this->requiredParams, "accountid", "device", "gamemodel", "gamesessionid", "gametype", "gpgameid", "gpid", "nogsgameid", "product");
        $this->validateRequiredParams();
        $this->user = User::findById(Request::input("accountid"));
        $this->validateLogin();
        $this->response->addAttribute("request", "getbalance");
        if (!$this->rc) {
            $this->response->addChild("APIVERSION", Request::input("apiversion"));
            $this->response->addChild("BALANCE",
                ($this->user->balance->balance_available + $this->user->balance->bonus));
        }
    }

    private function wager() {
        array_push($this->requiredParams, "accountid", "betamount", "device", "gamemodel", "gamesessionid", "gametype",
            "gpgameid", "gpid", "nogsgameid", "product", "roundid", "transactionid");
        $this->validateRequiredParams();
        $this->user = User::findById(Request::input("accountid"));
        $this->validateLogin();
        if ($this->validadeWagerUniqueness()){
            $this->bet = [
                'user_id' => $this->user->id,
                'api_bet_id' => Request::input("gpid") . "-" . Request::input("roundid"),
                'api_bet_type' => "nyx" . "-" . Request::input("product"),
                'api_transaction_id' => Request::input("gpid") . "-" . Request::input("transactionid"),
                'amount' => Request::input("betamount")*1,
                'currency' => "EUR",
                'user_session_id' => $this->user->getLastSession()->id,
                'status' => 'waiting_result'
            ];
            $this->user->newBet($this->bet);
        }
        $this->response->addAttribute("request", "wager");
        if (!$this->rc) {
            $this->response->addChild("APIVERSION", Request::input("apiversion"));
            $this->response->addChild("REALMONEYBET", Request::input("betamount"));
            //TODO: newBet needs to return how it has distributed the money bet (bonus/real)
            $this->response->addChild("BONUSMONEYBET", 0);
            $this->response->addChild("BALANCE", $this->user->balance->total());
            $this->response->addChild("ACCOUNTTRANSACTIONID", Request::input("gpid")."-".Request::input("transactionid"));
        }
    }

    private function result() {
        array_push($this->requiredParams, "accountid", "device", "gamemodel",
            "gamesessionid", "gamestatus", "gametype", "gpgameid", "gpid",
            "nogsgameid", "product", "result", "roundid", "transactionid");
        $this->validateRequiredParams();
        $this->user = User::findById(Request::input("accountid"));
        $this->validateLogin();
        if ($this->user)
            $this->bet = $this->user->getUserBetByBetId(Request::input("gpid")."-".Request::input("roundid"));
        $this->validateWagerExistence();
        if ($this->validadeResultUniqueness()) {
            $this->user->updateBet($this->bet, Request::input("result")*1);
        }
        $this->response->addAttribute("request", "result");
        if (!$this->rc) {
            $this->response->addChild("APIVERSION", Request::input("apiversion"));
            $this->response->addChild("BALANCE", $this->user->balance->balance_available);
            $this->response->addChild("ACCOUNTTRANSACTIONID", "69");
        }
    }

    private function validateRequiredParams() {
        if ($this->rc) return;
        foreach ($this->requiredParams as $param)
            if (!Request::exists($param)) {
                $this->setError(1008, "Missing parameter");
                break;
            }
    }

    private function validateAuthentication() {
        if ($this->rc) return;
        if (!(Request::input("loginname") === $this->loginname) || !(Request::input("password") === $this->password))
            $this->setError(1003, "Authentication failed");
    }

    private function validateLogin() {
        if ($this->rc) return; 
        if (!$this->user)
            $this->setError(1000, "Invalid session ID");
    }

    private function validadeWagerUniqueness() {
        if ($this->rc) return false;
        if ($this->user && $this->user->checkIfTransactionExists(Request::input("transactionid"))) {
            $this->setError(0, "Duplicate wager");
            return false;
        }
        return true;
    }

    private function validateWagerExistence() {
        if ($this->rc) return;
        if (!$this->bet)
            $this->setError(102, "Wager not found");
    }

    private function validadeResultUniqueness() {
        if ($this->rc) return false;
        if ($this->bet && $this->bet->status === "processed") {
            $this->setError(0, "Duplicate result");
            return false;
        }
        return true;
    }
}

