<?php

namespace App\Models;

use App\Enum\EventEnum;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Block extends Model
{
    protected $fillable = ['name', 'template_id', 'start_date', 'exit_criterion'];

    protected $casts = [
        'start_date' => 'datetime',
        'exit_criterion' => 'string',
        'created_at' => 'datetime',
        'template_id' => 'integer',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    /* public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'block_customer');
    } */



    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }


    /* public function segment(): HasOne
    {
        return $this->hasOne(Segmentation::class);
    } */

    public function segment(): HasOne
    {
        return $this->hasOne(Segment::class);
    }


    protected static function booted()
    {
        static::saving(function ($block) {
            // Obtén el valor de filters.exit_criterion desde la campaña asociada
            if (!$block->exit_criterion) {
                $block->exit_criterion = $block->campaign->filters['exit_criterion'] ?? null;
            }
        });
    }
}
