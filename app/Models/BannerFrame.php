<?php

namespace App\Models;

use App\Traits\Favoritable;
use App\Traits\CasinoDatabase;
use App\Traits\MainDatabase;
use Illuminate\Database\Eloquent\Model;


class BannerFrame extends Model
{
    use MainDatabase;

    protected $table = "banners_frames";

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}