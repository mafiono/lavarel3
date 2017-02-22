<?php

namespace App\Traits;

trait SrijDatabase
{
    public function __construct(array $attributes = [])
    {
        $this->table = config('app.srij_events_db') . '.' .$this->table;

        parent::__construct($attributes);
    }

    public static function alias($alias) {
        return (new static)->table . ' as ' . $alias;
    }
}
