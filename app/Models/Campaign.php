<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Campaign extends Model
{
    protected $fillable = ['name', 'start_date', 'end_date', 'filters', 'type_campaign'];

    protected $casts = [
        'filters' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'type_campaign' => 'string',
    ];

    public function blocks(): HasMany
    {
        return $this->hasMany(Block::class);
    }

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'campaign_customers')
            ->withTimestamps();
    }
}
