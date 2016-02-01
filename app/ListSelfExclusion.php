<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;

class ListSelfExclusion extends Model
{
    protected $table = 'list_self_exclusions';
    
    /**
     * Validates if an user has auto-excluded himself
     *
     * @return mix array or null
     */    
    public static function validateSelfExclusion($data)
    {
        return $selfExclusion = self::where('document_number', '=', $data['document_number'])
                                    ->where('end_date', '>=', Carbon\Carbon::now()->toDateTimeString())
                                    ->first();        
    }
}
