<?php

namespace App\Traits;


trait CasinoDatabase
{
    public static function alias($alias) {
        return (new static)->table . ' as ' . $alias;
    }

    public function __construct(array $attributes = [])
    {
        $this->table = config('app.casino_db').'.'.$this->table;

        parent::__construct($attributes);
    }
}