<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'external_id',
        'number',
        'date',
        'last_change_date',
        'price',
        'discount_percent',
        'warehouse_name',
        'oblast',
        'income_id',
        'nm_id',
        'subject',
        'category',
        'brand',
        'is_cancel',
        'cancel_dt',
    ];
}
