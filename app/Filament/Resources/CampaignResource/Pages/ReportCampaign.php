<?php

namespace App\Filament\Resources\CampaignResource\Pages;

use Filament\Resources\Pages\Page;
use App\Filament\Resources\CampaignResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

use App\Models\Campaign;
use App\Models\Sale;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Segmentation;
use App\Models\SalesComparative;
use App\Models\Segment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;


class ReportCampaign extends Page
{
    protected static string $resource = CampaignResource::class;

    protected static string $view = 'filament.resources.campaign-resource.pages.report-campaign';

    protected static ?string $title = 'Custom Page Title';

    use InteractsWithRecord;

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }


    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client_name')
                    ->label('Cliente')
                    ->sortable(),
                TextColumn::make('sales_before')
                    ->label('Ventas Antes')
                    ->sortable(),
                TextColumn::make('sales_after')
                    ->label('Ventas Después')
                    ->sortable(),
                TextColumn::make('revenues_before')
                    ->label('Ingresos Antes')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('revenues_after')
                    ->label('Ingresos Después')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('returns_before')
                    ->label('Valor Devoluciones Antes')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('returns_after')
                    ->label('Valor Devoluciones Después')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('orders_before')
                    ->label('Órdenes Antes')
                    ->sortable(),
                TextColumn::make('orders_after')
                    ->label('Órdenes Después')
                    ->sortable(),
                TextColumn::make('delivered_before')
                    ->label('Entregadas Antes')
                    ->sortable(),
                TextColumn::make('delivered_after')
                    ->label('Entregadas Después')
                    ->sortable(),
                TextColumn::make('returns_number_before')
                    ->label('Devoluciones Antes')
                    ->sortable(),
                TextColumn::make('returns_number_after')
                    ->label('Devoluciones Después')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([]);
    }
}
