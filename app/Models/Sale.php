<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'external_id',
        'g_number',
        'date',
        'last_change_date',
        'price',
        'discount_percent',
        'warehouse_name',
        'region_name',
        'income_id',
        'nm_id',
        'subject',
        'category',
        'brand',
        'is_supply',
        'is_realization',
        'for_pay',
        'finished_price',
        'price_with_disc',
    ];
}
