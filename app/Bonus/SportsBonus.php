<?php

namespace App\Bonus;


use App\User;
use Illuminate\Support\Facades\Auth;

class SportsBonus
{
    protected $user;

    public function __construct(User $user=null)
    {
        $this->user = $user ? $user : Auth::user();
    }

    public function hello(){return 'hello';}

    public function available(User $user=null)
    {
        return "hello";
    }

    final public function consumed(User $user=null)
    {

    }

    final public function getActive(User $user=null)
    {

    }
}