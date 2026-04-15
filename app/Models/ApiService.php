<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiService extends Model
{
    protected $table = 'api_services';
    protected $fillable = [
        'name',
    ];
}
