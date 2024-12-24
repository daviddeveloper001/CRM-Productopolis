<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'is_frequent_customer',
        'city_id',
        'country_id',
        'is_client'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'is_frequent_customer' => 'boolean',
        'city_id' => 'integer',
        'country_id' => 'integer'
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    //Set

    /* public function segmentions(): HasMany
    {
        return $this->hasMany(Segmentation::class);
    } */


    /* public function segmentations()
    {
        return $this->belongsToMany(Segmentation::class, 'segment_registers', 'customer_id', 'segment_id')
                    ->withTimestamps(); 
    } */

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function blocks() : BelongsToMany
    {
        return $this->belongsToMany(Block::class, 'block_customer');
    }

    public function segments() : BelongsToMany
    {
        return $this->belongsToMany(Segment::class, 'customer_segments', 'customer_id', 'segment_id')->withTimestamps();
    }
    
}
