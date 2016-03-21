<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 * @property int id
 * @property string name
 * @property string nationality
 * @property string cod_num
 * @property string cod_alf2
 * @property string cod_alf3
 */
class Country extends Model {
    protected $table = 'countries';
}
