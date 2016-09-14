<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Highlight extends Model
{
    public function scopeCompetitions($query)
    {
        $query->where('type','competition');
    }
}
