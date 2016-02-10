<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class CasinoGames extends Model {
    protected $table = "casino_games";

    public static function featuredGames() {
        return self::where("featured", "1")->get(["id", "name", "image_url"]);
    }

    public static function games($typeId) {
        return self::where("game_type_id", $typeId)->get(["id", "name", "image_url"]);
    }
}
