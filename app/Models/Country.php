<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 * @property int id
 * @property string name
 * @property string nationality
 */
class Country extends Model {
    protected $table = 'countries';
}
