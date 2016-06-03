<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Carbon start_date
 * @property Carbon end_date
 * @property string document_number
 * @property string document_type_id
 */
class ListSelfExclusion extends Model
{
    protected $table = 'list_self_exclusions';
    protected $dates = ['start_date', 'end_date'];
    
    /**
     * Validates if an user has auto-excluded himself
     *
     * @return ListSelfExclusion
     */    
    public static function validateSelfExclusion($data)
    {
        return $selfExclusion = self::where('document_number', '=', $data['document_number'])
            ->where(function($query){
                $query
                    ->whereNull('end_date')
                    ->orWhere('end_date', '>', Carbon::now()->toDateTimeString());
            })
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
