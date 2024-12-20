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
        'event_start_date',
        'event_start_time',
        'event_end_date',
        'event_end_time',
        'event_title',
        'event_description',
        'event_created_at',
        'event_attended',
    ];

    protected $casts = [
        'event_start_date' => 'date',
        'event_end_date' => 'date',
        'event_start_time' => 'datetime',
        'event_end_time' => 'datetime',
        'event_created_at' => 'datetime',
        'event_attended' => 'boolean',
    ];

    public function customer() : BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
