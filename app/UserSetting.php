<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $table = 'user_settings';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'chat',
        'email',
        'mail',
        'newsletter',
        'sms',
        'phone',
        'user_id',
        'user_session_id'
    ];

    /**
     * Relation with User
     *
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Updates user settings
     *
     * @param $inputs
     * @param $user_id
     * @param $user_session_id
     * @return UserSetting
     */
    public static function updateSettings($inputs, $user_id, $user_session_id)
    {
        $settings = [
            'chat' => 0,
            'email' => 0,
            'mail' => 0,
            'newsletter' => 0,
            'sms' => 0,
            'phone' => 0
        ];
        foreach ($settings as $key => $value)
            $settings[$key] = array_key_exists($key, $inputs) && $inputs[$key]?1:0 ;

        $settings['user_session_id'] = $user_session_id;

        if ($userSetting = UserSetting::find($user_id))
            return $userSetting->update($settings);

        $settings['user_id'] = $user_id;

        return UserSetting::create($settings);
    }

    public static function createInitialSettings($userId, $userSessionId)
    {
        $settings = [
            'chat' => 1,
            'email' => 1,
            'mail' => 1,
            'newsletter' => 1,
            'sms' => 1,
            'phone' => 1
        ];
        return self::updateSettings($settings, $userId, $userSessionId);
    }
}
