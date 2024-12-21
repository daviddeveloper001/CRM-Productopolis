<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerSegment extends Model
{
    protected $fillable = ['segment_id', 'customer_id'];

    protected $table = 'customer_segments';
}
