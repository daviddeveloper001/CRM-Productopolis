<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Block extends Model
{
    protected $fillable = ['name', 'template_id', 'start_date', 'end_date', 'exit_criterion'];

    public function template() : BelongsTo
    {
        return $this->belongsTo(Template::class);
    }
}
