<?php

namespace App\Models;

use App\Traits\MainDatabase;
use Illuminate\Database\Eloquent\Model;

class GolodeouroValue extends Model
{
    use MainDatabase;
    protected $table = 'cp_fixture_values';

}
