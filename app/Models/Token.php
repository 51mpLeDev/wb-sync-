<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $fillable = [
        'account_id',
        'api_service_id',
        'token_type_id',
        'value',
        'expires_at'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function apiService()
    {
        return $this->belongsTo(ApiService::class);
    }

    public function tokenType()
    {
        return $this->belongsTo(TokenType::class);
    }
}
