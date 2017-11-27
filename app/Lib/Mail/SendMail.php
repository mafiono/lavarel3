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
    public static $TYPE_12_SELF_EXCLUSION = 'self-exclusion';
    public static $TYPE_13_ACCOUNT_SUSPENDED = 'suspended';
    public static $TYPE_14_ACCOUNT_DISABLED = 'disabled';
    public static $TYPE_15_INACTIVE_30 = 'inactive-30';
    public static $TYPE_16_INACTIVE_90 = 'inactive-90';
    public static $TYPE_17_INACTIVE_120 = 'inactive-120';
    public static $TYPE_18_INACTIVE_PLUS_120 = 'inactive-121';
    public static $TYPE_19_IDENTITY_EXPIRED = 'identity-expired';
    public static $TYPE_20_COMPLAIN = 'complaint';
    public static $TYPE_21_COMPLAIN_RESULT = 'complaint-result';
    public static $TYPE_22_NEW_MESSAGE = 'message';

    public function __construct($type = 'basic')
    {
        $this->_type = $type;
    }

    private function generateOptions($user, array $options = [])
    {
        $server = config('app.server_url');

        $this->_options = array_replace_recursive([
            'user' => $user,
            'name' => $user->username,
            'email' => $user->email ?? $user->profile->email,
            'title' => 'THIS IS A TITLE',
            'url' => $server,
            'host' => $server,
            'button' => 'CONFIRMAR',
            'nr' => '00001',
            'exclusion' => 'other',
            'time' => '5',
            'value' => '20,00',
            'motive' => 'Uso indevido!',
        ], $options);
        if ($this->_options['url'][0] === '/') {
            $serverParts = explode('//', $server);
            $this->_options['url'] = $serverParts[0] . '//' . str_replace('//', '/', $serverParts[1] . $this->_options['url']);
        }
        if ($this->_options['host'][0] === '/') {
            $this->_options['host'] = str_replace('//', '/', $server . $this->_options['host']);
        }
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
            Log::error('Error sending mail, userId: ' .  $mail->user_id . ': ' . $e->getMessage());

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

                $h = $m->getSwiftMessage()->getHeaders();
                $h->addTextHeader('X-Custom-Data', 'UM:' . $mail->id);
                $h->addTextHeader('X-Open-Tracking-Enabled', 'true');
                $h->addTextHeader('X-Click-Tracking-Enabled', 'true');

                $mail->message = $m->getBody();
                $mail->save();
            });
    }
    private function _sendRetry($mail) {
        Mail::raw('Templating!!',
            function ($m) use ($mail) {
                $m->to($mail->to, $mail->username)->subject($mail->title);
                $m->setBody($mail->message,'text/html');

                $h = $m->getSwiftMessage()->getHeaders();
                $h->addTextHeader('X-Custom-Data', 'UM:' . $mail->id);
                $h->addTextHeader('X-Open-Tracking-Enabled', 'true');
                $h->addTextHeader('X-Click-Tracking-Enabled', 'true');
            });
    }
}