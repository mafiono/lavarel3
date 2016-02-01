<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthStep3Test extends TestCase
{
    // use DatabaseTransactions;

    /**
     * Accessing Step 2 redirect to step 1.
     *
     * @return void
     */
    public function testRedirectToStep1()
    {
        $this->visit('/registar/step3')
            ->seePageIs('/registar/step1');
    }

    /**
     * Test filling the Form of Step 2.
     *
     * @return void
     */
    public function testFillForm()
    {

    }
}
