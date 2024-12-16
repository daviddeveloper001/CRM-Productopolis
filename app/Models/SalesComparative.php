<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesComparative extends Model
{
    protected $fillable = [
        'client_name',
        'sales_before',
        'sales_after',
        'revenues_before',
        'revenues_after',
        'returns_before',
        'returns_after',
        'orders_before',
        'orders_after',
        'delivered_before',
        'delivered_after',
        'returns_number_before',
        'returns_number_after',
    ];
}
