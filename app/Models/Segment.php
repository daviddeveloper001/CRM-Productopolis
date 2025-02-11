<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Segment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'block_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'block_id' => 'integer',
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }


    public function customers() : BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'customer_segments', 'segment_id', 'customer_id')->withTimestamps();
    }

    /* public function registers()
    {
        return $this->hasMany(SegmentRegister::class);
    } */

    public function registers()
    {
        return $this->hasMany(CustomerSegment::class);
    }

    public function block(): BelongsTo
    {
        return $this->belongsTo(Block::class); 
    }
}
