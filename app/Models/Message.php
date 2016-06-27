<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserComplain
 * int id
 * string complaint
 * int user_id
 * int staff_id
 * int staff_session_id
 * int user_session_id
 * Carbon data
 * Carbon solution_time
 * string solution
 * string result
 * int created_at
 * int updated_at
 */
class Message extends Model
{
    protected $table = 'messages';
}
