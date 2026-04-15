<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = [
        'external_id',
        'date',
        'last_change_date',
        'date_close',
        'quantity',
        'price',
        'warehouse_name',
        'nm_id',
        'barcode',
        'account_id',
    ];
}
