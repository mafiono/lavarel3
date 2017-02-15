<?php

namespace App\Traits;


trait CasinoDatabase
{
    public function __construct(array $attributes = [])
    {
        $this->table = config('app.casino_db').'.'.$this->table;

        parent::__construct($attributes);
    }
}