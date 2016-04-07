<?php

namespace App\Models;

use App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserInvite
 * @property int id
 * @property int user_id
 * @property int user_friend_id
 * @property string user_promo_code
 * @property Carbon regist_date
 * @property string email
 * @property string status_id
 * @property double bet_sum
 * @property int created_at
 * @property int updated_at
 */
class UserInvite extends Model {
    protected $table = 'user_invites';
    protected $dates = ['regist_date'];

    /**
     * Relation with User
     *
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    /**
     * Relation with User Friend
     *
     */
    public function friend()
    {
        return $this->belongsTo('App\User', 'user_friend_id', 'id');
    }
    /**
     * Relation with Status
     *
     */
    public function status()
    {
        return $this->hasOne('App\Status', 'id', 'status_id');
    }
    /**
     * Create a new User Invite
     *
     * @param $friendId
     * @param $userId
     * @param $promo_code
     * @param $email
     * @return bool
     */
    public static function createInvite($friendId, $userId, $promo_code, $email)
    {
        $invite = new UserInvite();
        $invite->user_id = $friendId;
        $invite->user_friend_id = $userId;
        $invite->user_promo_code = $promo_code;
        $invite->email = $email;
        $invite->status_id = 'pending';
        $invite->bet_sum = 0;
        $invite->regist_date = Carbon::now()->toDateTimeString();

        return $invite->save();
    }
}
