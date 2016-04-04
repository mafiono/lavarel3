<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Status
 * @property string id
 * @property string name
 * @property string bo_name
 */
class Status extends Model
{
    protected $table = 'statuses';
    
}
