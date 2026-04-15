<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'external_id',
        'date',
        'warehouse_name',
        'nm_id',
        'quantity',
        'in_way_to_client',
        'in_way_from_client',
        'barcode',
        'account_id',
    ];
}
