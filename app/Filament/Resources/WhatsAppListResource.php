<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShopResource\Pages;
use App\Models\WhatsAppList;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WhatsAppListResource extends Resource
{
    protected static ?string $model = WhatsAppList::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Configuraciones';

    protected static ?string $pluralModelLabel = 'WhatsApp | Listas';

    protected static ?string $modelLabel = 'WhatsApp | Lista';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('button_text')
                    ->label('Texto del botón')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('footer_text')
                    ->label('Texto del pie de la lista')
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListWhatsAppLists::route('/'),
            'create' => Pages\CreateWhatsAppList::route('/create'),
            'edit' => Pages\EditWhatsAppList::route('/{record}/edit'),
        ];
    }
}
