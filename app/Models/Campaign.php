<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Campaign extends Model
{
    protected $fillable = ['name', 'template_id'];

    public function template() : BelongsTo
    {
        return $this->belongsTo(Template::class);
    }
}
