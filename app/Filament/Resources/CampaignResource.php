<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\City;
use App\Models\Shop;
use Filament\Tables;
use App\Models\Block;
use App\Models\Seller;
use App\Enum\EventEnum;
use Filament\Forms\Get;
use App\Models\Campaign;
use App\Models\Template;
use Filament\Forms\Form;
use App\Models\Department;
use Filament\Tables\Table;
use App\Models\ReturnAlert;
use App\Models\SegmentType;
use App\Models\Segmentation;
use App\Enum\TypeSegmentEnum;
use App\Models\PaymentMethod;
use Filament\Resources\Resource;
use App\Enum\TypeSegmentationEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\CampaignResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CampaignResource\RelationManagers;


class CampaignResource extends Resource
{
    protected static ?string $model = Campaign::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Configuraciones';

    protected static ?string $pluralModelLabel = 'Campañas';

    protected static ?string $modelLabel = 'Campaña';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Campaña')
                    ->collapsible()
                    ->columns([
                        'sm' => 3,
                        'xl' => 4,
                        '2xl' => 12,
                    ])
                    ->description('Información de la campaña')
                    ->schema([
                        TextInput::make('name')
                            ->label('Campaña')
                            ->required()
                            ->maxLength(400)
                            ->columnSpan(12),
                        DatePicker::make('start_date')
                            ->label('Fecha de inicio')
                            ->required()
                            ->columnSpan(6),
                        DatePicker::make('end_date')
                            ->label('Fecha de finalización')
                            ->required()
                            ->columnSpan(6),
                    ]),

                Section::make('Segmentación de campañas')
                    ->collapsible()
                    ->description('Seleccionar tipo de segmentación')
                    ->schema([
                        Select::make('type_segment')
                            ->label('Tipo de segmentación')
                            ->enum(TypeSegmentEnum::class)
                            ->options(TypeSegmentEnum::class)
                            ->live()
                    ]),

                Section::make('Filtros de segmentación')
                    ->collapsible()
                    ->description('Agregar filtros a la segmentación')
                    ->schema([
                        Section::make('Información del segmento')
                            ->columns([
                                'sm' => 3,
                                'xl' => 4,
                                '2xl' => 8,
                            ])
                            ->schema([
                                Select::make('payment_method_id')
                                    ->label('Método de pago')
                                    ->options(PaymentMethod::all()->pluck('name', 'id'))
                                    //->searchable()
                                    ->preload()
                                    ->columnSpan([
                                        'sm' => 2,
                                        'xl' => 3,
                                        '2xl' => 4,
                                    ]),

                                Select::make('alert_id')
                                    ->label('Alertas de devolución')
                                    ->options(ReturnAlert::all()->pluck('type', 'id'))
                                    ->preload()
                                    ->columnSpan([
                                        'sm' => 2,
                                        'xl' => 3,
                                        '2xl' => 4,
                                    ]),
                                Select::make('department_id')
                                    ->label('Departamento')
                                    //->searchable()
                                    ->preload()
                                    ->options(Department::all()->pluck('name', 'id'))
                                    ->columnSpan([
                                        'sm' => 2,
                                        'xl' => 3,
                                        '2xl' => 4,
                                    ]),

                                Select::make('city_id')
                                    ->label('Ciudad')
                                    //->searchable()
                                    ->preload()
                                    ->options(City::all()->pluck('name', 'id'))
                                    ->columnSpan([
                                        'sm' => 2,
                                        'xl' => 3,
                                        '2xl' => 4,
                                    ]),

                                TextInput::make('limit')
                                    ->label('Limite')
                                    ->columnSpan([
                                        'sm' => 2,
                                        'xl' => 3,
                                        '2xl' => 4,
                                    ]),
                                Select::make('segment_type_id')
                                    ->label('Tipo de Segmento')
                                    //->searchable()
                                    ->preload()
                                    ->options(SegmentType::all()->pluck('name', 'id'))
                                    ->columnSpan([
                                        'sm' => 2,
                                        'xl' => 3,
                                        '2xl' => 4,
                                    ]),

                                Select::make('seller_id')
                                    ->label('Vendedor')
                                    //->searchable()
                                    ->preload()
                                    ->options(Seller::all()->pluck('name', 'id'))
                                    ->columnSpan([
                                        'sm' => 2,
                                        'xl' => 3,
                                        '2xl' => 4,
                                    ]),

                                Select::make('shop_id')
                                    ->label('Tienda')
                                    //->searchable()
                                    ->preload()
                                    ->options(Shop::all()->pluck('name', 'id'))
                                    ->columnSpan([
                                        'sm' => 2,
                                        'xl' => 3,
                                        '2xl' => 4,
                                    ]),
                            ]),
                    ])
                    ->visible(fn($get) => $get('type_segment') === 'ProductPolis'),

                Section::make('Bloques')
                    ->collapsible()
                    ->description('Agregar bloques a la campaña')
                    ->schema([
                        Repeater::make('blocks')
                            ->collapsible()
                            ->relationship('blocks')
                            ->label('Bloques')
                            ->schema(Block::getForm())
                            ->columnSpan(12),
                    ])
                    ->visible(fn($get) => $get('type_segment') === 'Medical')

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Campaña')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Fecha de inicio')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Fecha de finalización')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListCampaigns::route('/'),
            'create' => Pages\CreateCampaign::route('/create'),
            'edit' => Pages\EditCampaign::route('/{record}/edit'),
        ];
    }
}
