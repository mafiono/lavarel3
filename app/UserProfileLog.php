<?php

namespace App;

use App\Traits\MainDatabase;
use Illuminate\Database\Eloquent\Model;
use DB;

class UserProfileLog extends Model {
    use MainDatabase;
    protected $table = 'user_profiles_log';

    protected $fillable =  [
        'user_id',
        'username',
        'alias',
        'account',
        'payment_type',
        'document_number',
        'document_type_id',
        'name',
        'birth_date',
        'tax_number',
        'address',
        'zip_code',
        'nationality',
        'phone',
        'email',
        'tax_authority_reply_id',
        'tax_authority_replay',
        'action_code',
        'status_code',
        'motive',
        'descr_acao',
        'dutation',
        'start_date',
        'end_date',
        'original_date'
    ];

    public function setUpdatedAt($value){}

    /**
     * Logs user profile data for captor later use.
     *
     * @param $userId
     * @param bool $force
     * @return UserProfileLog | bool
     */
    public static function createLog($userId, $force = false) {
        $userData = DB::table(User::alias('u'))
            ->where('u.id', '=', $userId)
            ->leftJoin(UserProfile::alias('up'), 'u.id', '=', 'up.user_id')
            ->first();
        $current = DB::table(self::alias('u'))
            ->where('user_id', '=', $userId)
            ->orderBy('created_at', 'desc')
            ->first();
        if ($force || self::isChanged($userData, $current)) {
            return UserProfileLog::create(json_decode(json_encode($userData), true));
        }
        return false;
    }

    private static function isChanged($new, $old) {
        if ($old === null)
            return true;

        $fields = [
            'document_number',
            'document_type_id',
            'name',
            'birth_date',
            'tax_number',
            'address',
            'zip_code',
            'nationality',
            'phone',
            'email',
        ];

        foreach ($fields as $key) {
            if ($new->{$key} !== $old->{$key}) return true;
        }
        return false;
    }
}