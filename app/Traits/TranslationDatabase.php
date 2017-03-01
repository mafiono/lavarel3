<?php

namespace App\Traits;

trait TranslationDatabase
{
    public function __construct(array $attributes = [])
    {
        $this->table = config('app.lang_db') . '.' .$this->table;

        parent::__construct($attributes);
    }

    public static function alias($alias) {
        return (new static)->table . ' as ' . $alias;
    }
}
