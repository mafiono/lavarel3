<?php
//
//  A simple PHP CAPTCHA script
//
//  Copyright 2011 by Cory LaViska for A Beautiful Site, LLC
//
//  See readme.md for usage, demo, and licensing info
//

namespace App\Lib\Mail;

use App\Models\UserMail;
use Exception;
use Log;
use Mail;
use Request;

class SendMail
{
    private $_options;
    private $_mail;
    private $_type;

    public static $TYPE_1_SIGN_UP_SUCCESS = 'sign-success';
    public static $TYPE_2_SIGN_UP_IDENTITY = 'sign-identity';
    public static $TYPE_3_CONFIRM_EMAIL = 'confirm-email';
    public static $TYPE_4_NEW_DEPOSIT = 'deposit';
    public static $TYPE_5_WITHDRAW_REQUEST = 'withdraw';
    public static $TYPE_7_WITHDRAW_SUCCESS = 'withdraw-success';
    public static $TYPE_8_BONUS_ACTIVATED = 'bonus';
    public static $TYPE_9_BET_RETURNED = 'bet-returned';
    public static $TYPE_10_RESET_PASSWORD = 'reset-password';
    public static $TYPE_11_LOGIN_FAIL = 'fail-login';

    public function __construct($type = 'basic')
    {
        $this->_type = $type;
    }

    private function generateOptions($user, array $options = [])
    {
        $this->_options = array_replace_recursive([
            'user' => $user,
            'name' => $user->username,
            'email' => $user->email ?? $user->profile->email,
            'title' => 'THIS IS A TITLE',
            'url' => Request::getUriForPath('/'),
            'host' => Request::getUriForPath('/'),
            'button' => 'CONFIRMAR',
            'nr' => '00001',
            'exclusion' => 'other',
            'time' => '5',
            'value' => '20,00',
            'motive' => 'Uso indevido!'
        ], $options);
    }

    public function prepareMail($user, array $options = [], $userSessionId = null) {
        $this->generateOptions($user, $options);
        $vars = $this->_options;

        $this->_mail = $mail = new UserMail();
        $mail->user_id = $user->id;
        $mail->user_session_id = $userSessionId;
        $mail->username = $vars['name'];
        $mail->title = $vars['title'];
        $mail->to = $vars['email'];
        $mail->type = $this->_type;
        $mail->message = 'Building';
        $mail->save();
    }

    public function retryMail($id) {
        $this->_mail = UserMail::find($id);
    }


    public function Send($throw = false, $retry = false) {
        try {
            $vars = $this->_options;
            /** @var UserMail $mail */
            $mail = $this->_mail;

            if ($retry) {
                $this->_sendRetry($mail);
            } else {
                $this->_sendNormal($vars, $mail);
            }
            $mail->sent = true;
            $mail->save();
            return true;
        } catch (Exception $e) {
            Log::error('Error sending mail: ' . $e->getMessage());

            $mail->sent = false;
            $mail->tries++;
            $mail->error = $e->getCode() . ': ' . $e->getMessage() . "\n " . $e->getTraceAsString();
            $mail->save();

            if ($throw) { throw $e; }
            return false;
        }
    }

    private function _sendNormal($vars, $mail) {
        Mail::send('emails.types.' . $this->_type, $vars,
            function ($m) use ($mail) {
                $m->to($mail->to, $mail->username)->subject($mail->title);
                $mail->message = $m->getBody();
                $mail->save();
            });
    }
    private function _sendRetry($mail) {
        Mail::raw('Templating!!',
            function ($m) use ($mail) {
                $m->to($mail->to, $mail->username)->subject($mail->title);
                $m->setBody($mail->message,'text/html');
            });
    }
}