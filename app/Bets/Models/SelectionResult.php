<?php

namespace App\Bets\Models;

use App\Traits\OddsDatabase;
use Illuminate\Database\Eloquent\Model;

class SelectionResult extends Model
{
    use OddsDatabase;
    protected $table = "selection_results";

    protected $primaryKey = 'selection_id';

    public function selection()
    {
        return $this->belongsTo(Selection::class);
    }
}