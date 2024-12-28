<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerSegment extends Model
{
    protected $fillable = ['customer_id','segment_id', 'last_purchase_at'];

    protected $table = 'customer_segments';
}
