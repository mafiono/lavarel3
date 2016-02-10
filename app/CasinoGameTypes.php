<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class CasinoGameTypes extends Model {
    protected $table = "casino_game_types";

    /**
     * Get game types
     * @return mixed
     */
    public static function types($exceptions = []) {
        $gameTypes = (new self());
        foreach ($exceptions as $exception)
            $gameTypes = $gameTypes->where("id","!=",$exception);

        return $gameTypes->orderBy("position")->get(["id", "name", "css_icon"]);
    }

    /**
     * Get game type name
     * @param $typeId
     * @return mixed
     */
    public static function typeName($typeId) {
        return CasinoGameTypes::find($typeId)->toArray()["name"];
    }
}
