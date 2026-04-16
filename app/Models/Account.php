<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'company_id',
        'name',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function tokens()
    {
        return $this->hasMany(Token::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getToken(string $serviceName, string $typeName)
    {
        return $this->tokens()
            ->whereHas('apiService', fn($q) => $q->where('name', $serviceName))
            ->whereHas('tokenType', fn($q) => $q->where('name', $typeName))
            ->first();
    }
}
