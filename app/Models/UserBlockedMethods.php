<?php
namespace App\Models;

use App\Traits\MainDatabase;
use Illuminate\Database\Eloquent\Model;

class UserBlockedMethods extends Model {
    use MainDatabase;
    protected $table = 'user_blocked_methods';

}