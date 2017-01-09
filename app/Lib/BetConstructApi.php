<?php 
namespace App\Lib;

use Config, Request, Route, Session;
use WebSocket\Client;

/**
* BetConstruct - Helper class to connect to BetConstruct API
* 
* 
* @package    App
* @subpackage Lib
*/


class BetConstructApi {

   protected $url = "ws://swarm-partner.betconstruct.com";
   protected $siteId = 234;
   protected $client;


    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new Client($this->url);
    }
   private function requestSession()
   {
     $params = [
         "command" => "request_session",
         "params" => [
             "site_id" => $this->siteId,
             "language" => "eng"
         ]
     ];

     $this->send($params);

     $session = $this->receive();
     
     if (!isset($session->sid))
      return false;

     return $session->sid;
   }

   public function send($params)
   {
      $this->client->send(json_encode($params));
   }

   public function receive()
   {
      $result = json_decode($this->client->receive());

      if (!isset($result->code) || $result->code != 0) {
         dd($result);
         return false;
      }

      return $result->data;
   }

   public function signUp($user)
   {
      if (!$session = $this->requestSession())
         return false;

      $names = explode(' ', $user->profile->name);

      $params = [
         "command" => "register_user",
         "sid" => $session,
         "params" => [
            "user_info" => [
               "username" => $user->username,
               "password" => $user->password,
               "first_name" => $names[0],
               "last_name" => sizeof($names) > 1 ? end($names) : "",
               "gender" => $user->profile->title == 'mr' ? "M" : "F",
               "city" => $user->profile->city,
               "birth_date" => date("Y-m-d", strtotime($user->profile->birth_date)),
               "address" => $user->profile->address,
               "country_code" => strtoupper($user->profile->country),
               "email" => $user->profile->email,
               "phone" => $user->profile->phone,
               "currency_name" => "EUR",
               "doc_number" => $user->profile->tax_number,
               "site_id" => $this->siteId,
               "security_question" => "What is this security question about?",
               "security_answer" => "ptcasinopt",
               "notify_via_email" => false,
               "notify_via_sms" => null
            ]
         ]
      ];

      $params = [
         "command" => "restore_login",
         "params" => [
               "user_id" => $user->id,
               "auth_token" => $session
               //"password" => $user->password,         
            // "user_info" => [
            //    "username" => $user->username,
            //    "password" => $user->password,
            //    "first_name" => $names[0],
            //    "last_name" => sizeof($names) > 1 ? end($names) : "",
            //    "gender" => $user->profile->title == 'mr' ? "M" : "F",
            //    "city" => $user->profile->city,
            //    "birth_date" => date("Y-m-d", strtotime($user->profile->birth_date)),
            //    "address" => $user->profile->address,
            //    "country_code" => strtoupper($user->profile->country),
            //    "email" => $user->profile->email,
            //    "phone" => $user->profile->phone,
            //    "currency_name" => "EUR",
            //    "doc_number" => $user->profile->tax_number,
            //    "site_id" => $this->siteId,
            //    "security_question" => "What is this security question about?",
            //    "security_answer" => "ptcasinoibetup",
            //    "notify_via_email" => false,
            //    "notify_via_sms" => null
            // ]
         ]
      ];      

      $this->send($params);

       return true;
      $result = $this->receive();

      print_r($session);

      dd($result);


   }
}