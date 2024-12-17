<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    protected $fillable = ['name', 'start_date', 'end_date'];

    public function segments(): HasMany
    {
        return $this->hasMany(Segmentation::class);
    }

    public function blocks()
    {
        return $this->belongsToMany(Block::class, BlockCampaign::class, 'campaign_id', 'block_id');
    }
}
