<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ApiRequestLog
 *
 */
class ApiRequestLog extends Model
{
    protected $table = 'api_request_logs';

  	protected $fillable = ['request'];
}
