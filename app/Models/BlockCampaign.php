<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockCampaign extends Model
{
    protected $table = 'block_campaign';

    protected $fillable = [
        'block_id',
        'campaign_id',
    ];
}
