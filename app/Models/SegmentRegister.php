<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SegmentRegister extends Model
{
    protected $fillable = ['segment_id', 'customer_id'];


    protected $table = 'segment_registers';

}
