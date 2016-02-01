<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListIdentityCheck extends Model
{
    protected $table = 'list_identity_checks';
    
    /**
     * Validates if an user has submitted valid information about himself
     *
     * @return mix array or null
     */    
    public static function validateIdentity($data)
    {
        $identity = self::where('tax_number', '=', $data['tax_number'])
                          ->where('name', '=', $data['name'])
                          ->where('birth_date', '=', $data['birth_date'])
                          ->first();

        if (!$identity)
            return false;

        if ($identity['deceased'] || $identity['under_age'])
            return false;

        return true;
    }
}
