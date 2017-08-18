<?php

namespace app\Bonus\Casino;


use Illuminate\Support\Facades\Facade;

class CasinoBonusFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'casino.bonus';
    }
}