<?php

namespace App\Models;

use App;
use App\Traits\MainDatabase;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserInvite
 * @property int id
 * @property integer user_id
 * @property integer user_session_id
 * @property string type
 * @property string title
 * @property string message
 * @property string to
 * @property boolean resend
 * @property string error
 * @property boolean sent
 * @property integer tries
 */
class UserMail extends Model {
    use MainDatabase;
    protected $table = 'user_mails';

    /**
     * Relation with User
     *
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
