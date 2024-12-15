<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'event_start',
        'event_end',
        'event_title',
        'event_description'
    ];

    public function customer() : BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
