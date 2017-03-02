<?php

namespace App\Traits;

trait OddsDatabase
{
    public function __construct(array $attributes = [])
    {
        $this->table = config('app.odds_db') . '.' .$this->table;

        parent::__construct($attributes);
    }

    public static function alias($alias) {
        return (new static)->table . ' as ' . $alias;
    }
}
