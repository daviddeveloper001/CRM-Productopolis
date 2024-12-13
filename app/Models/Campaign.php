<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Campaign extends Model
{
    protected $fillable = ['name', 'segment_id', 'start_date', 'end_date'];

    public function segment() : BelongsTo
    {
        return $this->belongsTo(Segmentation::class);
    }
}
