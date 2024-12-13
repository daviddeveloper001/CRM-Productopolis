<?php

namespace App\Models;

use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'orders_number',
        'delivered',
        'returns_number',
        'date_first_order',
        'date_last_order',
        'last_order_date_delivered',  
        'total_sales',
        'total_revenues',
        'return_value',
        'payment_method_id',
        'seller_id',
        'shop_id',
        'last_item_purchased',
        'previous_last_item_purchased',
        'days_since_last_purchase',
        'return_alert_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'last_order_date_delivered' => 'date',
        'total_sales' => 'decimal:2',
        'total_revenues' => 'decimal:2',
        'return_value' => 'decimal:2',
        'customer_id' => 'integer',
        'shop_id' => 'integer',
        'seller_id' => 'integer',
        'method_id' => 'integer',
        'return_alert_id' => 'integer',
        'payment_method_id' => 'integer',
        'date_first_order' => 'date',
        'date_last_order' => 'date',
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

    public function segmentRegisters() : HasMany
    {
        return $this->hasMany(SegmentRegister::class);
    }

    public function segment_type() : BelongsTo
    {
        return $this->belongsTo(SegmentType::class);
    }



}
