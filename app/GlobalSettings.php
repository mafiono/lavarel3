<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GlobalSettings extends Model {
    protected $table = 'global_settings';
    public $timestamps = false;

    public static function getTax() {
        return 0.08;
    }
}