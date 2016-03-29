<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;

class UserSetting extends Model
{
    protected $table = 'user_settings';
    protected $fillable = [
        'chat',
        'email',
        'mail',
        'newsletter',
        'sms',
        'phone'
    ];
  /**
    * Relation with User
    *
    */
    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

  /**
    * Changes an user setting
    *
    * @param array data
    *
    * @return boolean true or false
    */
    public static function updateSettings() {
        $settings = [
            'chat' => 0,
            'email' => 0,
            'mail' => 0,
            'newsletter' => 0,
            'sms' => 0,
            'phone' => 0
        ];

        foreach ($settings as $key => $value) {
            $settings[$key] = (Input::get($key)==='on')?1:0;
        };
        dd($settings);
//        $data['user_id'] = $this->user->id;
//        $data['user_session_id'] = Session::get('user_session');
        return UserSetting::save($settings);
    }

}
