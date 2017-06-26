<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ListIdentityCheck
 * @property boolean deceased
 * @property boolean under_age
 * @property boolean valido
 * @property Carbon birth_date
 * @property string id_cidadao
 * @property string name
 * @property string response
 * @property string tax_number
 */
class ListIdentityCheck extends Model
{
    protected $table = 'list_identity_checks';
    
    /**
     * Validates if an user has submitted valid information about himself
     *
     * @return array
     */    
    public static function validateIdentity($cc, $nif, $birth, $name)
    {
        /** @var ListIdentityCheck $identity */
        $identity = self::query()
            ->where('id_cidadao', '=', $cc)
            ->where('name', '=', $name)
            ->where('birth_date', '=', $birth)
            ->first();

        if ($identity === null) {
            return [
                'exists' => false,
                'valido' => false
            ];
        }

        return [
            'exists' => true,
            'valido' => $identity->valido
        ];
    }
}
