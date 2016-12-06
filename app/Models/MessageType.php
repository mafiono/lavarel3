<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageType extends Model
{
    protected $table = 'message_types';

    public function customer()
    {
        return $this->belongsTo(Staff::class);
    }
}
