<?php

namespace App\Models;

use App\Traits\MainDatabase;
use Illuminate\Database\Eloquent\Model;

class MarketingEmail extends Model
{
    use MainDatabase;
    protected $table = "marketing_emails";
}