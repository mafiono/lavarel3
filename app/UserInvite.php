<?php

namespace App;

use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Session;


class UserInvite extends Model
{
    protected $table = 'user_invites';
    
  /**
    * Relation with User
    *
    */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

  /**
    * Relation with Status
    *
    */
    public function status()
    {
        return $this->belongsTo('App\Status', 'status_id', 'id');
    }
    /**
     * Creates a new user status
     *
     * @param $status
     * @param string $type
     *
     * @return object UserStatus
     */

}
