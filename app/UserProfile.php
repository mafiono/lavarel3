<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $table = 'user_profiles';
    
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
            'name' => $data['name'],
            'email' => $data['email'],
            'email_checked' => 0,
            'email_token' => $token,
            'birth_date' => $data['birth_date'],
            'nationality' => $data['nationality'],
            'profession' => $data['profession'],
            'address' => $data['address'],
            'zip_code' => $data['zip_code'],
            'phone' => $data['phone'],
            'city' => $data['city'],
            'country' => $data['country'],
            'document_number' => $data['document_number'],
            'document_type_id' => 'cartao_cidadao',
            'tax_number' => $data['tax_number'],
            'user_session_id' => $userSessionId
        ];

        foreach ($profileData as $key => $value)
        	$this->$key = $value;

        if (!$this->save())
        	return false;

        return true;
    }

  /**
    * Updates an User Profile
    *
    * @param array data
    *
    * @return boolean true or false
    */
    public function updateProfile($data, $userSessionId) 
    {
        $profileData = [
            'profession' => $data['profession'],
            'address' => $data['address'],
            'zip_code' => $data['zip_code'],
            'phone' => $data['phone'],
            'city' => $data['city'],
            'country' => $data['country'],
            'user_session_id' => $userSessionId
        ];

        foreach ($profileData as $key => $value)
            $this->$key = $value;

        if (!$this->save())
            return false;

        return true;
    }    
    
}
