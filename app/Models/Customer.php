<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_id',
        'phone',
        'email',
        'is_frequent_customer',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name_id' => 'integer',
        'is_frequent_customer' => 'boolean',
    ];

    public function name(): BelongsTo
    {
        return $this->belongsTo(Name::class);
    }

    public function segmentations(): BelongsToMany
    {
        return $this->belongsToMany(Segmentation::class);
    }
}
