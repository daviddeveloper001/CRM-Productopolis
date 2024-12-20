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

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'block_customer');
    }



    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }


    public function segment(): HasOne
    {
        return $this->hasOne(Segmentation::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($block) {
            if (!$block->exit_criterion && $block->campaign) {
                $block->exit_criterion = $block->campaign->filters['exit_criterion'] ?? null;
            }
        });
    }



    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->label('Nombre')
                ->required()
                ->maxLength(255),
            Select::make('template_id')
                ->label('Plantilla')
                ->relationship('template', 'name')
                ->required(),
            DateTimePicker::make('start_date')
                ->label('Fecha de inicio')
                ->required(),

            Select::make('exit_criterion')
                ->label('Criterio de salida')
                ->enum(EventEnum::class)
                ->options(EventEnum::class)
                ->hidden()
                ->default($data['exit_criterion'] ?? null)
        ];
    }
}
