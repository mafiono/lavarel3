<?php

namespace App\Models;

use App\Traits\MainDatabase;
use Illuminate\Database\Eloquent\Model;

class GolodeouroSelection extends Model
{
    use MainDatabase;
    protected $table = 'cp_selections';

}
