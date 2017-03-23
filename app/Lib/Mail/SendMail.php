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
            'value' => '20,00',
            'nr' => '00001',
            'exclusion' => 'other',
            'time' => '5',
            'motive' => 'Uso indevido!'
        ], $options);
    }

    public function prepareMail($user, $userSessionId, array $options = []) {
        $this->generateOptions($user, $options);
        $vars = $this->_options;

        $this->_mail = $mail = new UserMail();
        $mail->user_id = $user->id;
        $mail->user_session_id = $userSessionId;
        $mail->title = $vars['title'];
        $mail->to = $vars['email'];
        $mail->type = 'basic';
        $mail->message = 'Building';

        $mail->save();
    }

    public function Send($throw = false) {
        try {
            $vars = $this->_options;
            /** @var UserMail $mail */
            $mail = $this->_mail;

            Mail::send('emails.types.' . $this->_type, $vars,
                function ($m) use ($vars, $mail) {
                    $m->to($vars['email'], $vars['name'])->subject($vars['title']);
                    $mail->message = $m->getBody();
                    $mail->save();
                });
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
}