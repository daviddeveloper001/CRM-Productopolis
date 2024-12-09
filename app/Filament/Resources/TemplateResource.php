<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CityResource\Pages;
use App\Models\Sale;
use App\Models\Template;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

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
                                            ->label('[NOMBRE_CLIENTE]'),
                                        Placeholder::make('2')
                                            ->label('[TELEFONO_CLIENTE]'),
                                        Placeholder::make('3')
                                            ->label('[EMAIL_CLIENTE]'),
                                        Placeholder::make('3')
                                            ->label('[CIUDAD_CLIENTE]'),
                                        Placeholder::make('3')
                                            ->label('[TIENDA]'),
                                        Placeholder::make('3')
                                            ->label('[VENDEDOR]')
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
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsVisibility('public')
                                    ->fileAttachmentsDirectory('templates')
                                    ->required()
                                    ->columnSpan([
                                        'sm' => 12,
                                        'xl' => 12,
                                        '2xl' => 12,
                                    ])
                                    ->visible(fn($get) => $get('type') === 'email')
                                    ->live(),
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
                                        return Sale::all()->mapWithKeys(function ($sale) {
                                            return [$sale->id => 'Venta #' . $sale->id];
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
                                                parseWhatsAppFormatting(
                                                    replacePlaceholders(
                                                        $get('content'),
                                                        $get('preview_with')
                                                    )
                                                )
                                            )
                                            : new HtmlString(
                                                replacePlaceholders(
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
                    ->html()
                    ->formatStateUsing(fn($state) => nl2br(e($state)))
                    ->wrap()
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

function parseWhatsAppFormatting($text)
{
    $text = preg_replace('/\*(.*?)\*/', '<strong>$1</strong>', $text);

    $text = preg_replace('/_(.*?)_/', '<em>$1</em>', $text);

    $text = preg_replace('/~(.*?)~/', '<del>$1</del>', $text);

    $text = preg_replace('/```(.*?)```/', '<code>$1</code>', $text);

    $text = preg_replace('/`(.*?)`/', '<code>$1</code>', $text);

    return nl2br($text);
}

function replacePlaceholders($content, $saleId)
{
    $sale = Sale::find($saleId);

    if (!$sale) {
        return $content;
    }

    $placeholders = [
        '[NOMBRE_CLIENTE]' => $sale->customer->customer_name,
        '[TELEFONO_CLIENTE]' => $sale->customer->phone,
        '[EMAIL_CLIENTE]' => $sale->customer->email,
        '[CIUDAD_CLIENTE]' => $sale->customer->city->name,
        '[VENDEDOR]' => $sale->seller->name,
        '[TIENDA]' => $sale->shop->name,
    ];

    foreach ($placeholders as $placeholder => $value) {
        $content = str_replace($placeholder, $value, $content);
    }

    return $content;
}
