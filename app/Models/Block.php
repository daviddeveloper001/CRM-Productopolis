<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Block extends Model
{
    protected $fillable = ['name', 'template_id', 'start_date', 'exit_criterion'];

    protected $casts = [
        'start_date' => 'datetime',
        'exit_criterion' => 'string',
        'created_at' => 'datetime',
        'template_id' => 'integer',
    ];

    public function template() : BelongsTo
    {
        return $this->belongsTo(Template::class);
    }
    
}
