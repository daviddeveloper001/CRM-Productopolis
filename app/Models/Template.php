<?php

namespace App\Models;

use App\Utils\FormatUtils;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\HtmlString;

class Template extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'content',
        'whatsapp_list_id',
        'attachment'
    ];


    public function campaign(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(Block::class);
    }

    public function whatsappList(): HasOne
    {
        return $this->hasOne(WhatsAppList::class);
    }

    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->label('Nombre')
                ->required()
                ->maxLength(150)
                ->columnSpan([
                    'sm' => 12,
                    'xl' => 12,
                    '2xl' => 12,
                ]),
            Select::make('type')
                ->label('Tipo')
                ->options([
                    'whatsapp' => 'WhatsApp',
                    'email' => 'E-mail'
                ])
                ->required()
                ->columnSpan([
                    'sm' => 12,
                    'xl' => 12,
                    '2xl' => 12,
                ])
                ->live(),
            Group::make()
                ->schema([
                    Section::make('Contenido')
                        ->schema([
                            Placeholder::make('placeholders')
                                ->label('Comodines')
                                ->content('Los comodines son variables que se reemplazarán por valores específicos en el contenido de la plantilla.')
                                ->columnSpan([
                                    'sm' => 12,
                                    'xl' => 12,
                                    '2xl' => 12,
                                ]),
                            Grid::make([
                                'default' => 1,
                                'sm' => 2,
                                'md' => 3,
                                'lg' => 4,
                                'xl' => 6,
                                '2xl' => 8,
                            ])
                                ->schema([
                                    Placeholder::make('1')
                                        ->label('[NOMBRE-CLIENTE]'),
                                    Placeholder::make('2')
                                        ->label('[TELEFONO-CLIENTE]'),
                                    Placeholder::make('3')
                                        ->label('[EMAIL-CLIENTE]'),
                                    Placeholder::make('3')
                                        ->label('[CIUDAD-CLIENTE]'),
                                    Placeholder::make('3')
                                        ->label('[EVENT-START-DATE]'),
                                    Placeholder::make('3')
                                        ->label('[EVENT-END-DATE]'),
                                    Placeholder::make('3')
                                        ->label('[EVENT-TITLE]'),
                                    Placeholder::make('3')
                                        ->label('[EVENT-DESCRIPTION]')
                                ])
                                ->columns(3),
                            Textarea::make('content')
                                ->label('Contenido')
                                ->rows(10)
                                ->required()
                                ->columnSpan([
                                    'sm' => 12,
                                    'xl' => 12,
                                    '2xl' => 12,
                                ])
                                ->visible(fn($get) => $get('type') === 'whatsapp')
                                ->live(),
                            RichEditor::make('content')
                                ->label('Contenido')
                                ->fileAttachmentsDirectory('templates')
                                ->required()
                                ->columnSpan([
                                    'sm' => 12,
                                    'xl' => 12,
                                    '2xl' => 12,
                                ])
                                ->visible(fn($get) => $get('type') === 'email')
                                ->live(),
                            FileUpload::make('attachment')
                                ->label('Archivos')
                                ->columnSpan([
                                    'sm' => 12,
                                    'xl' => 12,
                                    '2xl' => 12,
                                ])
                        ])
                        ->columnSpan([
                            'sm' => 12,
                            'xl' => 6,
                            '2xl' => 6,
                        ]),
                    Section::make('Previsualización')
                        ->schema([
                            Select::make('preview_with')
                                ->label('Previsualizar con')
                                ->options(function () {
                                    return Customer::all()->mapWithKeys(function ($customer) {
                                        return [$customer->id => $customer->first_name . ' ' . $customer->last_name];
                                    });
                                })
                                ->columnSpan([
                                    'sm' => 12,
                                    'xl' => 12,
                                    '2xl' => 12,
                                ])
                                ->live(),
                            Placeholder::make('preview')
                                ->content(
                                    fn($get) => $get('type') === 'whatsapp'
                                        ? new HtmlString(
                                            FormatUtils::parseWhatsAppFormatting(
                                                FormatUtils::replaceCustomerPlaceholders(
                                                    $get('content'),
                                                    $get('preview_with')
                                                )
                                            )
                                        )
                                        : new HtmlString(
                                            FormatUtils::replaceCustomerPlaceholders(
                                                $get('content'),
                                                $get('preview_with')
                                            )
                                        )
                                )
                                ->columnSpan([
                                    'sm' => 12,
                                    'xl' => 12,
                                    '2xl' => 12,
                                ]),
                        ])
                        ->columnSpan([
                            'sm' => 12,
                            'xl' => 6,
                            '2xl' => 6,
                        ]),
                ])
                ->visible(fn($get) => !empty($get('type')))
                ->columnSpan([
                    'sm' => 12,
                    'xl' => 12,
                    '2xl' => 12,
                ]),
            Select::make('whatsapp_list_id')
                ->label('Listado de opciones')
                ->relationship('whatsappList', 'title')
                ->visible(fn($get) => $get('type') === 'whatsapp')
                ->preload()
                ->createOptionForm([
                    Grid::make()
                        ->schema([
                            Section::make()
                                ->schema([
                                    TextInput::make('title')
                                        ->required()
                                        ->maxLength(400),
                                    Textarea::make('description')
                                        ->maxLength(65535),
                                    TextInput::make('button_text')
                                        ->maxLength(255),
                                    TextInput::make('footer_text')
                                        ->maxLength(255),
                                ])
                                ->columnSpan(12),
                            Section::make()
                                ->schema([
                                    Repeater::make('sections')
                                        ->relationship('sections')
                                        ->schema([
                                            TextInput::make('title')
                                                ->required()
                                                ->maxLength(255),
                                            Repeater::make('options')
                                                ->relationship('options')
                                                ->schema([
                                                    TextInput::make('title')
                                                        ->required()
                                                        ->maxLength(255),
                                                    Textarea::make('description')
                                                        ->maxLength(65535),
                                                ]),
                                        ]),
                                ])
                                ->columnSpan(12),
                        ])
                        ->columns(12),
                ])
                ->columnSpan([
                    'sm' => 12,
                    'xl' => 12,
                    '2xl' => 12,
                ]),
        ];
    }
}
