<?php

namespace App;

use App\Providers\RulesValidator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer id
 * @property integer doc_type_id
 * @property string document_number
 * @property string nation_id
 * @property string document_type_id
 * @property Carbon start_date
 * @property Carbon end_date
 * @property boolean confirmed
 * @property string origin
 * @property boolean changed
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
        $cc = RulesValidator::CleanCC($data['document_number'], false);
        /** @var ListSelfExclusion $selfExclusion */
        $selfExclusion = self::query()
            ->where('document_number', 'LIKE', $cc . '%')
            ->where(function($query){
                $query
                    ->whereNull('end_date')
                    ->orWhere('end_date', '>', Carbon::now()->toDateTimeString());
            })
            ->first();
        return $selfExclusion;
    }

    /**
     * @param $profile UserProfile
     * @param $userSelfExclusion UserSelfExclusion
     * @return ListSelfExclusion|bool
     */
    public static function addSelfExclusion($profile, $userSelfExclusion)
    {
        $types = [
            'bi' => '0', // BI (ID CARD)
            'cartao_cidadao' => '1', // CARTAO_CIDADAO (CITIZEN CARD)
            'passaporte' => '2', // PASSAPORTE (PASSPORT)
            'nif' => '3', // NUMERO IDENTIFIC FISCAL (TAX IDENTIFICATION NUMBER)
            'outro' => '4', // OUTRO (OTHER)
        ];

        $selfExclusion = new ListSelfExclusion;
        $selfExclusion->doc_type_id = $types[$profile->document_type_id];
        $selfExclusion->document_number = $profile->document_number;
        $selfExclusion->document_type_id = $profile->document_type_id;
        $selfExclusion->start_date = $userSelfExclusion->request_date;
        $selfExclusion->end_date = $userSelfExclusion->end_date;
        $selfExclusion->nation_id = $profile->nationality;
        $selfExclusion->origin = 'sfpo';

        if (!$selfExclusion->save())
            return false;

        return $selfExclusion;
    }
}
