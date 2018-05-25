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
        'whatsapp',
        'consent',
        'user_id',
        'user_session_id',
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
            'email' => 0,
            'phone' => 0,
            'sms' => 0,
            'newsletter' => 0,
            'whatsapp' => 0,
        ];
        foreach ($settings as $key => $value)
            $settings[$key] = array_key_exists($key, $inputs) && $inputs[$key]?1:0;

        $settings['user_session_id'] = $user_session_id;

        if ($userSetting = UserSetting::find($user_id))
            return $userSetting->update($settings);

        $settings['user_id'] = $user_id;

        return UserSetting::create($settings);
    }

    public function giveConsent($user_session_id)
    {
        $this->consent = 1;

        $this->user_session_id = $user_session_id;

        return $this->save();
    }

    public static function createInitialSettings($userId, $userSessionId)
    {
        $settings = [
            'user_id' => $userId,
            'user_session_id' => $userSessionId,
            'chat' => 1,
            'email' => 1,
            'mail' => 1,
            'newsletter' => 1,
            'sms' => 1,
            'phone' => 1,
            'whatsapp' => 1,
            'consent' => 1,
        ];
        return UserSetting::create($settings);
    }
}
