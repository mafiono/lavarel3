<?php

namespace App;

use App\Models\Country;
use App\Traits\MainDatabase;
use Illuminate\Database\Eloquent\Model;
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

        UserProfileLog::createLog($userId);

        return true;
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
