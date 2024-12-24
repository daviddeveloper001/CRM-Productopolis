<?php

namespace App\Models;

use App\Enum\EventEnum;
use App\Enum\TypeCampaignEnum;
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
use Filament\Tables\Columns\TextColumn;
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
        'attachment',
        'campaign_type',
        'event_type',
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
                ->label('Canal de comunicación')
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
            Select::make('campaign_type')
                ->label('Para la empresa')
                ->Enum(TypeCampaignEnum::class)
                ->options(TypeCampaignEnum::class)
                ->required()
                ->columnSpan([
                    'sm' => 12,
                    'xl' => 12,
                    '2xl' => 12,
                ])
                ->live(),
            Select::make('event_type')
                ->label('Para el evento')
                ->options(function (callable $get) {
                    return TypeCampaignEnum::getEvents($get('campaign_type')) ?? [];
                })
                ->required()
                ->columnSpan([
                    'sm' => 12,
                    'xl' => 12,
                    '2xl' => 12,
                ])
                ->visible(fn($get) => $get('campaign_type') !== null)
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $wildcards = TypeCampaignEnum::getEventWildcards($get('campaign_type'), $state);

                    $set('textos', $wildcards);
                })
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
                            Grid::make(3) // Número de columnas en la cuadrícula
                                ->schema(function ($get) {
                                    $textos = $get('textos') ?? [];
                                    $result = array_map(function ($texto, $index) {
                                        return Placeholder::make('Comodín #' . $index)->label($texto);
                                    }, $textos, array_keys($textos));

                                    return $result;
                                })
                                ->columnSpan(12),
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
                            Placeholder::make('preview')
                                ->content(
                                    fn($get) => $get('type') === 'whatsapp'
                                        ? new HtmlString(
                                            FormatUtils::parseWhatsAppFormatting($get('content'))
                                        )
                                        : $get('content')
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
                ->visible(
                    fn($get) => !empty($get('type')) &&
                        !empty($get('campaign_type')) &&
                        !empty($get('event_type'))
                )
                ->columnSpan([
                    'sm' => 12,
                    'xl' => 12,
                    '2xl' => 12,
                ]),
            Select::make('whatsapp_list_id')
                ->label('Listado de opciones')
                ->options(WhatsAppList::all()->pluck('title', 'id'))
                ->nullable()
                ->visible(fn($get) => $get('type') === 'whatsapp')
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
