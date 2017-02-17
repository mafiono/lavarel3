<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;

class SportsMarketsMultiple extends Model
{
    protected $table = 'sports_markets_multiples';

    public static function getId($markets)
    {
        if (count($markets) === 0)
            throw new Exception('Aposta vazia!');
        if (count($markets) === 1)
            return $markets[0];

        $key = implode('|', asort($markets));
        $item = self::query()->where('markets', '=', $key)->first();
        if ($item !== null)
            return $item->id;

        $item = new self();
        $item->markets = $key;
        $item->save();
        return $item->id;
    }

}
