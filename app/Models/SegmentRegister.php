<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SegmentRegister extends Model
{
    protected $fillable = ['segment_id', 'sale_id'];

    public function segment() : BelongsTo
    {
        return $this->belongsTo(Segmentation::class);
    }

    public function sale() : BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
}
