<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogApiClient extends Model
{
    protected $table = 'logs_api_client';
    protected $fillable = ['tag', 'level', 'message', 'context', 'ip', 'method', 'url', 'header', 'body', 'created_by'];


}
