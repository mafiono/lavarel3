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

    final public static function available(User $user=null)
    {
    }

    final public static function consumed(User $user=null)
    {

    }

    final public static function getActive(User $user=null)
    {

    }
}