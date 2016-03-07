<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class T101NyxControllerTest //extends TestCase
{
//    public function setUp() {
//        parent::setUp();
//        $this->markTestSkipped();
//    }

    public function testNoParams() {
        $this->visit('/nyx_wallet')
             ->see('rc="110"');
    }
    public function testPing() {
        $this->visit('/nyx_wallet?request=ping&loginname=nogsuser&password=qwerty&apiversion=1.2')
            ->see('rc="0"');
        //echo $this->response->content();
    }
    public function testGetAccount() {
        $this->visit('/nyx_wallet?request=getaccount&loginname=nogsuser&password=qwerty&apiversion=1.2&sessionid=u7n143s3a3e7a6d66abe92d080b344b38aeb41b1fae1bc9&device=desktop')
            ->see('rc="0"');
        //echo $this->response->content();
    }
    public function testGetBalance() {
        $this->visit('/nyx_wallet?request=getbalance&loginname=nogsuser&password=qwerty&apiversion=1.2&gamesessionid=AAD8EE30-8C43-11DC-9755-668156D89593&device=desktop&accountid=7&product=casino&gametype=slots&gamemodel=5reels&gpid=100&nogsgameid=70002&gpgameid=madmadmonkey')
            ->see('rc="0"');
//        echo $this->response->content();
    }

    public function testWager() {
        $this->visit('/nyx_wallet?roundid=113&product=casino&loginname=nogsuser&gpgameid=1&gamemodel=5reels&request=wager&gametype=slots&device=desktop&gpid=1000&gamesessionid=AAD8EE30-8C43-11DC9755-668156D89593&apiversion=1%2E2&betamount=2.5&transactionid=20&password=qwerty&nogsgameid=70001&accountid=7')
            ->see('rc="0"');
        echo $this->response->content();
    }

    public function testResult() {
        $this->visit('/nyx_wallet?request=result&loginname=nogsuser&password=qwerty&apiversion=1.2&gamesessionid=AAD8EE30-8C43-11DC-9755-668156D89593&accountid=7&nogsgameid=456&result=10&roundid=113&transactionid=20&product=casino&gametype=slots&gamemodel=5reels&gamestatus=completed&gpid=1000&gpgameid=madmadmonkey&device=desktop')
//        $this->visit('/nyx_wallet?roundid=113&product=casino&loginname=nogsuser&nogsgameid=70001&gamemodel=5reels&request=result&gametype=slots&gpgameid=1&gpid=1000&gamesessionid=AAD8EE30-8C43-11DC9755-668156D89593&apiversion=1%2E2&rollbackamount=2.5&device=desktop&transactionid=1206&password=qwerty&accountid=7')
            ->see('rc="0"');
        echo $this->response->content();
    }

}