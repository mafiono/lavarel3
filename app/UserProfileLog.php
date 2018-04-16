<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class UserProfileLog extends Model {
    protected $table = 'user_profiles_log';

    protected $fillable =  [
        "user_id",
        "username",
        "alias",
        "account",
        "payment_type",
        "document_number",
        "document_type_id",
        "name",
        "birth_date",
        "tax_number",
        "address",
        "zip_code",
        "nationality",
        "phone",
        "email",
        "tax_authority_reply_id",
        "tax_authority_replay",
        "action_code",
        "status_code",
        "motive",
        "descr_acao"
    ];

    public function setUpdatedAt($value){}

    /**
     * Logs user profile data for captor later use.
     *
     * @param $userId
     */
    public static function createLog($userId) {
        $userData = DB::table('users')->where('users.id', '=', $userId)
            ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->first();
        UserProfileLog::create(json_decode(json_encode($userData), true));
    }
}