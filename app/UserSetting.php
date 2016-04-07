<<<<<<< .mine
<?phpnamespace App;use Illuminate\Database\Eloquent\Model;=======
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
>>>>>>> .theirs
<<<<<<< .mine
class UserSetting extends Model{    protected $table = 'user_settings';    protected $primaryKey = 'user_id';    protected $fillable = [        'chat',        'email',        'mail',        'newsletter',        'sms',        'phone',        'user_id',        'user_session_id'    ];  /**    * Relation with User    *    */=======

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
>>>>>>> .theirs
     *     * @return boolean true or false
     */    public static function createInitialSettings($userId, $userSessionId)
    {        $settings = [            'chat' => 1,
            'email' => 1,
            'mail' => 1,
            'newsletter' => 1,
            'sms' => 1,
            'phone' => 1
        ];        return self::updateSettings($settings, $userId, $userSessionId);
    }}