<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_date',
        'last_order_date_delivered',
        'total_sales',
        'total_revenues',
        'orders_number',
        'number_entries',
        'returns_number',
        'return_value',
        'last_days_purchase_days',
        'last_item_purchased',
        'customer_id',
        'shop_id',
        'seller_id',
        'method_id',
        'segmentation_id',
        'return_alert_id',
        'payment_method_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'order_date' => 'date',
        'last_order_date_delivered' => 'date',
        'total_sales' => 'decimal:2',
        'total_revenues' => 'decimal:2',
        'return_value' => 'decimal:2',
        'customer_id' => 'integer',
        'shop_id' => 'integer',
        'seller_id' => 'integer',
        'method_id' => 'integer',
        'segmentation_id' => 'integer',
        'return_alert_id' => 'integer',
        'payment_method_id' => 'integer',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

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

    public function segmentation(): BelongsTo
    {
        return $this->belongsTo(Segmentation::class);
    }

    public function returnAlert(): BelongsTo
    {
        return $this->belongsTo(ReturnAlert::class);
    }
}
