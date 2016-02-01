<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NyxControllerTest extends TestCase
{
    public function testNoParams() {
        $this->visit('/nyx_wallet')
             ->see('rc="110"');
    }

    public function testPing() {
        $this->visit('/nyx_wallet?request=ping&loginname=nogsuser&password=qwerty&apiversion=1.2')
            ->see('rc="0"');
        echo $this->response->content();
    }
    public function testGetAccount() {
        $this->visit('/nyx_wallet?request=getaccount&loginname=nogsuser&password=qwerty&apiversion=1.2&sessionid=u7n143s3a3e7a6d66abe92d080b344b38aeb41b1fae1bc9&device=desktop')
            ->see('rc="0"');
        echo $this->response->content();
    }
    public function testGetBalance() {
        $this->visit('/nyx_wallet?request=getbalance&loginname=nogsuser&password=qwerty&apiversion=1.2&gamesessionid=AAD8EE30-8C43-11DC-9755-668156D89593 &device=desktop&accountid=7&product=casino&gametype=slots&gamemodel=5reels &gpid=100&nogsgameid=70002&gpgameid=madmadmonkey')
            ->see('rc="0"');
        echo $this->response->content();
    }

}
