<?php
//
//  A simple PHP CAPTCHA script
//
//  Copyright 2011 by Cory LaViska for A Beautiful Site, LLC
//
//  See readme.md for usage, demo, and licensing info
//

namespace App\Lib\Mail;

use Exception;
use Log;
use Mail;

class SendMail
{
    private $_options;

    public function __construct()
    {
    }

    public function generateOptions($user)
    {
        $this->_options = [
            'user' => $user,
            'name' => $user->username,
            'email' => $user->profile->email,
            'title' => 'THIS IS A TITLE',
            'url' => 'https://www.casinoportugal.pt',
            'host' => 'https://www.casinoportugal.pt',
            'button' => 'CONFIRMAR',
            'value' => '20,00',
            'nr' => '00001',
            'exclusion' => 'other',
            'time' => '5',
            'motive' => 'Uso indevido!'
        ];
    }

    public function Send($type = 'basic', $throw = false) {
        try {
            $vars = $this->_options;
            Mail::send('emails.types.' . $type, $vars,
                function ($m) use ($vars) {
                    $m->to($vars['email'], $vars['name'])->subject($vars['title']);
                });
            return true;
        } catch (Exception $e) {
            Log::error('Error sending mail: ' . $e->getMessage());
            if ($throw) { throw $e; }
            return false;
        }
    }
}