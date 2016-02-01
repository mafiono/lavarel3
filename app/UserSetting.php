<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $table = 'user_settings';

  /**
    * Relation with User
    *
    */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }  

  /**
    * Relation with Setting
    *
    */
    public function setting()
    {
        return $this->belongsTo('App\Setting', 'settings_type_id', 'id');
    }              

  /**
    * Creates a new Setting with all values true
    *
    * @param array data
    *
    * @return boolean true or false
    */
    public static function createInitialSettings($userId, $userSessionId) 
    {
        $types = Setting::lists('id', 'id');
        foreach ($types as $type) {
            $setting = new UserSetting();
            $setting->user_id = $userId;
            $setting->settings_type_id = $type;
            $setting->value = 1;
            $setting->user_session_id = $userSessionId;
            
            if (!$setting->save())
                return false;
        }

        return true;
    }

  /**
    * Changes an user setting
    *
    * @param array data
    *
    * @return boolean true or false
    */
    public static function updateSettings($data, $userSessionId) 
    {
        $setting = self::where('user_id', '=', $data['user_id'])
                         ->where('settings_type_id', '=', $data['type'])
                         ->first();

        if (! $setting)
            return false;

        $setting->value = $data['value'];
        $setting->user_session_id = $userSessionId;

        return $setting->save();
    }    

}
