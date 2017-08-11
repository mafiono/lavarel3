<?php
/**
 * Created by PhpStorm.
 * User: José.Couto
 * Date: 12/07/2017
 * Time: 15:46
 */

namespace App\Lib;

use Illuminate\Database\Eloquent\Builder;

class DebugQuery
{
    public static function make($query) {

        /** @var Builder $build */
        $build = $query;
        $queryStr = $build->toSql();
        $b = $build->getBindings();
        $formatted = '';
        $parts = explode('?', $queryStr);
        $i = 0;
        foreach ($parts as $p) {
            $formatted .= $p;
            if (isset($b[$i])) {
                $formatted .= '\'';
                $formatted .= $b[$i];
                $formatted .= '\'';
            }
            $i++;
        }
        dd($queryStr, $b, $formatted);
    }
}