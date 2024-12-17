<?php

namespace App\Filament\Resources;

use App\Models\Sale;
use Filament\Tables;
use App\Enum\EventEnum;
use App\Models\Customer;
use App\Models\Template;
use Filament\Forms\Form;
use App\Utils\FormatUtils;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\CityResource\Pages;

class TemplateResource extends Resource
{
    protected static ?string $model = Template::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Configuraciones';

    protected static ?string $pluralModelLabel = 'Plantillas';

    protected static ?string $modelLabel = 'Plantilla';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->searchable(),
                TextColumn::make('content')
                    ->label('Contenido')
                    ->html()->formatStateUsing(fn(string $state): HtmlString => new HtmlString($state))
                    ->lineClamp(10)
                    ->searchable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTemplate::route('/'),
            'create' => Pages\CreateTemplate::route('/create'),
            'edit' => Pages\EditTemplate::route('/{record}/edit'),
        ];
    }
}
