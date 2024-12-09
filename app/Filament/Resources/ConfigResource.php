<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CityResource\Pages;
use App\Models\Config;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ConfigResource extends Resource
{
    protected static ?string $model = Config::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Configuraciones';

    protected static ?string $pluralModelLabel = 'Parámetros';

    protected static ?string $modelLabel = 'Parámetro';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('key_')
                    ->label('Parámetro')
                    ->disabled()
                    ->required()
                    ->maxLength(150),
                TextInput::make('value')
                    ->label('Valor')
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key_')
                    ->label('Parámetro')
                    ->searchable(),
                TextColumn::make('value')
                    ->label('Valor')
                    ->numeric()
                    ->sortable()
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
            'index' => Pages\ListConfig::route('/'),
        ];
    }
}
