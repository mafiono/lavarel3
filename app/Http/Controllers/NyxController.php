<?php

namespace App\Http\Controllers;
use App\UserSession;
use App\User;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;

//use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
//use Session, View, Response, Auth, Mail, Validator, Input;
//use Parser;
//use DB;
//use App\ApiRequestLog, App\User, App\UserSession;
use SimpleXMLElement;
use SoapBox\Formatter\Formatter;


class NyxController extends Controller {
    private $loginname = "nogsuser";
    private $password = "qwerty";
    private $requiredParams;
    private $response;
    private $rc = 0;
    private $msg = "Sucess";
    private $ssid = "12345";
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
                break;
            case "result":
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
        if (!$this->rc) {
            $this->response->addAttribute("request", "ping");
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
        if (!$this->rc) {
            $this->response->addChild("APIVERSION", Request::input("apiversion"));
            $this->response->addChild("BALANCE", $this->user->balance->balance_available);
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
            $this->setError(1000, "Not logged on");
    }

    private function validateDevice() {
        if ($this->rc) return;
        if (!(Request::input("device") === "desktop") || !(Request::input("device") === "desktop"))
            $this->setError(1008, "Missing parameter");
    }
}