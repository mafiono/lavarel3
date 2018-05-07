<?php

namespace App;

use App\Lib\DebugQuery;
use App\Models\Country;
use App\Providers\RulesValidator;
use App\Traits\MainDatabase;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;
use function PHPSTORM_META\type;

/**
 * @property int user_id
 * @property mixed gender
 * @property mixed name
 * @property mixed email
 * @property mixed email_checked
 * @property mixed email_token
 * @property mixed birth_date
 * @property mixed nationality
 * @property mixed profession
 * @property mixed address
 * @property mixed zip_code
 * @property mixed phone
 * @property mixed city
 * @property mixed country
 * @property mixed document_number
 * @property mixed document_type_id
 * @property mixed tax_number
 * @property mixed user_session_id
 */
class UserProfile extends Model
{
    use MainDatabase;

    protected $table = 'user_profiles';

    public static function findByEmail($email)
    {
        return self::where('email', '=', $email)->first();
    }

    /**
    * Relation with Jogador
    *
    */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
  /**
    * Creates a new User Profile
    *
    * @param array data
    *
    * @return boolean true or false
    */
    public function createProfile($data, $userId, $userSessionId, $token)
    {
        $profileData = [
            'user_id' => $userId,
            'gender' => $data['gender'],
            'name' => $data['firstname']." ".$data['name'],
            'email' => $data['email'],
            'email_checked' => 0,
            'email_token' => $token,
            'birth_date' => $data['birth_date'],
            'nationality' => $data['nationality'],
            'professional_situation' => $data['sitprofession'],
            'profession' => $data['profession'],
            'address' => $data['address'],
            'zip_code' => $data['zip_code'],
            'phone' => $data['phone'],
            'city' => $data['city'],
            'country' => $data['country'],
            'document_number' => $data['document_number'],
            'document_type_id' => $data['document_type_id'] ?? 'cartao_cidadao',
            'tax_number' => $data['tax_number'],
            'user_session_id' => $userSessionId,
            'district' => $data['district'],
        ];

        foreach ($profileData as $key => $value)
        	$this->$key = $value;

        if (!$this->save())
        	return false;

        $this->createLogFromOldAccount($userId);

        return true;
    }

    private function createLogFromOldAccount($userId) {
        $log = UserProfileLog::createLog($userId, true);
        $account = self::getOldAccount($this->document_number, $userId);
        if ($account !== null) {
            // this account is reactivated
            $desc = 'Nova Conta ';
            $action = null;
            switch($account->type_id) {
                case 'canceled':
                    $desc .= 'vinda de Cancelamento (Rescisão)';
                    $action = 41;
                    break;
                case 'undetermined_period':
                    $desc .= 'de jogador (Autoexclusão Indet)';
                    $action = 10;
                    break;
                default:
                    $desc .= "vinda de Cancelamento ($account->type_id)";
                    $action = 99; // Unknown
                    break;
            }
            $log->motive = 'OLD_COD_JOGADOR: ' . $account->id;
            $log->descr_acao = $desc;
            $log->status_code = 89;
            $log->action_code = $action;
            $log->duration = 0;
            $log->start_date = Carbon::now()->toDateTimeString();
            $log->end_date = null;
            $log->original_date = $account->se_date;

            $log->save();
        }
    }

    public static function getOldAccount($cc, $userId) {
        $cc = RulesValidator::CleanCC($cc, false);
        $cc = ltrim($cc, '0');

        $query = DB::table(self::alias('up'))
            ->leftJoin(User::alias('u'), 'u.id', '=', 'up.user_id')
            ->leftJoin(UserSelfExclusion::alias('us'), 'u.id', '=', 'us.user_id')
            ->leftJoin(UserRevocation::alias('ur'), 'u.id', '=', 'ur.user_id')
            ->where('up.document_number', 'LIKE', '%'. $cc . '%')
            ->where('up.user_id', '!=', $userId)
            ->where(function ($q) {
                $q->whereNull('ur.id');
                $q->orWhere('us.id', '=', DB::raw('ur.self_exclusion_id'));
            })
            ->whereNotIn('us.self_exclusion_type_id', ['reflection_period'])
            ->orderBy('u.identity_date', 'desc', 'us.request_date', 'desc')
            ->select([
                'u.id',
                'u.identity_date',
                'up.document_number',
                'us.id as se_id',
                'us.self_exclusion_type_id as type_id',
                'us.request_date as se_date',
                'ur.request_date as sr_date',
            ])
        ;
//        DebugQuery::make($query);
        $list = $query->get();
        $ac = null;
        foreach ($list as $item) {
            $cItem = RulesValidator::CleanCC($item->document_number, false);
            $cItem = ltrim($cItem, '0');

            if ($cc === $cItem) {
                $ac = $item;
                break;
            }
        }
//        dd($list);
//        dd($query->get());
        return $ac;
    }

    /**
     * Updates an User Profile
     *
     * @param $data
     * @param $userSessionId
     * @return bool true or false
     */
    public function updateProfile($data, $userSessionId) 
    {
        $profileData = [
            'profession' => $data['profession'],
            'professional_situation' => $data['sitprofession'],
            'district' => $data['district'],
            'address' => $data['address'],
            'zip_code' => $data['zip_code'],
            'phone' => str_replace(' ', '', $data['phone']),
            'city' => $data['city'],
            'country' => $data['country'],
            'user_session_id' => $userSessionId
        ];

        foreach ($profileData as $key => $value)
            $this->$key = $value;

        if (!$this->save())
            return false;

        UserProfileLog::createLog($this->user_id);

        return true;
    }

    public function getGender() {
        switch ($this->gender) {
            case 'f': return 'Sr.';
            case 'm': return 'Sr.ª';
            default: return '';
        }
    }

    public function getNationality() {
        return Country::query()
            ->where('cod_alf2', '=', $this->nationality)
            ->value('nationality') ?? $this->nationality;
    }
}
