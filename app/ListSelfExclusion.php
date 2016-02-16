<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;

/**
 * @property  document_number
 */
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

    public static function addSelfExclusion($data)
    {
        $selfExclusion = new ListSelfExclusion;
        $selfExclusion->document_number = $data['document_number'];
        $selfExclusion->document_type_id = $data['document_type_id'];
        $selfExclusion->start_date = $data['start_date'];
        $selfExclusion->end_date = $data['end_date'];

        if (!$selfExclusion->save())
            return false;

        return $selfExclusion;
    }
}
