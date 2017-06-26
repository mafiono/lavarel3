<?php

namespace App\Models;

use App\Traits\MainDatabase;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class Ad extends Model
{
    use MainDatabase;

    protected $table = "ads";


    /**
     * Relation with BannerFrames
     *
     */


    public function formatImage() {
        $img = $this->image;
        if (strrpos($img, 'http') !== 0) {
            $this->image = env('FRONT_SERVER') . 'assets/portal/img/ads/' .$img;
        }
    }
}