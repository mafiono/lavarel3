<?php

namespace App\Models;

use App;
use App\Traits\MainDatabase;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserInvite
 * @property int id
 * @property int user_id
 * @property string origin
 * @property int version
 * @property int user_session_id
 * @property string entity
 * @property string ref
 * @property boolean active
 */
class UserDepositReference extends Model {
    use MainDatabase;
    protected $table = 'user_deposit_references';

    /**
     * Relation with User
     *
     */
    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id', 'id');
    }
}
