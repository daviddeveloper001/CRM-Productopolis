<?php

namespace App\Filament\Resources\ReturnAlertResource\Pages;

use App\Filament\Resources\ReturnAlertResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReturnAlerts extends ListRecords
{
    protected static string $resource = ReturnAlertResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
