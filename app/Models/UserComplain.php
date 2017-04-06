<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserComplain
 * @property int id
 * @property string complaint
 * @property int user_id
 * @property int staff_id
 * @property int staff_session_id
 * @property int user_session_id
 * @property Carbon data
 * @property Carbon solution_time
 * @property string solution
 * @property string result
 * @property int created_at
 * @property int updated_at
 */
class UserComplain extends Model
{
    protected $table = 'user_complains';
    protected $dates = ['data', 'solution_time'];

    public function staff() {
        return $this->hasOne('\App\Models\Staff', 'id', 'staff_id');
    }
}
