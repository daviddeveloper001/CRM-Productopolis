<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Block;
use App\Enum\EventEnum;
use App\Models\Campaign;
use App\Models\Template;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
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
                Forms\Components\TextInput::make('name')
                    ->label('Campaña')
                    ->required()
                    ->maxLength(400),
                Forms\Components\DatePicker::make('start_date')
                    ->label('Fecha de inicio')
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->label('Fecha de finalización')
                    ->required(),
                Forms\Components\Select::make('block_id')
                    ->label('Bloques')
                    ->searchable()
                    ->multiple()
                    ->preload()
                    ->options(Block::all()->pluck('name', 'id'))
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('template_id')
                            ->label('Plantilla')
                            //->relationship('template', 'name')
                            ->required(),
                        Forms\Components\DateTimePicker::make('start_date')
                            ->label('Fecha de inicio')
                            ->required(),

                        Forms\Components\Select::make('exit_criterion')
                            ->label('Criterio de salida')
                            ->enum(EventEnum::class)
                            ->options(EventEnum::class),
                    ]),
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
