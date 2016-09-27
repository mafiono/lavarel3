<?php

namespace App\Bets\Models;

use Illuminate\Database\Eloquent\Model;

class SelectionResult extends Model
{
    protected $connection = 'odds';

    protected $primaryKey = 'selection_id';

    public function selection()
    {
        return $this->belongsTo(Selection::class);
    }
}