<?php

namespace App\Models;

use App\Traits\MainDatabase;
use Illuminate\Database\Eloquent\Model;


class Banner extends Model
{
    use MainDatabase;

    protected $table = "banners";

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * Relation with BannerFrames
     *
     */
    public function frames()
    {
        return $this->hasMany(BannerFrame::class)
            ->orderBy('order_id');
    }

}