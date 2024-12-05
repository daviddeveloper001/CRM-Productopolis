<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_order_date',
        'last_order_date',
        'last_order_date_delivered',
        'seller_id',
        'shop_id',
        'payment_method_id',
        'total_order',
        'total_entries',
        'total_returns',
        'total_sales',
        'total_revenues',
        'return_value',
        'days_since_last_purchase',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'first_order_date' => 'date',
        'last_order_date' => 'date',
        'last_order_date_delivered' => 'date',
        'seller_id' => 'integer',
        'shop_id' => 'integer',
        'payment_method_id' => 'integer',
        'total_order' => 'decimal:2',
        'total_entries' => 'decimal:2',
        'total_returns' => 'decimal:2',
        'total_sales' => 'decimal:2',
        'total_revenues' => 'decimal:2',
        'return_value' => 'decimal:2',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
