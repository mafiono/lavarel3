<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaptorFiles extends Model
{
    protected $fillable = ['filename'];

    public function setUpdatedAt($value) {}

}
