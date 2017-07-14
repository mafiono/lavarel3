<?php

namespace App\Bets\Models;

use App\Traits\OddsDatabase;
use Illuminate\Database\Eloquent\Model;

class Selection extends Model
{
    use OddsDatabase;

    public function result()
    {
        return $this->hasOne(SelectionResult::class);
    }

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function scopeIds($query, $ids)
    {
        $query->whereIn('id', $ids);
    }
}