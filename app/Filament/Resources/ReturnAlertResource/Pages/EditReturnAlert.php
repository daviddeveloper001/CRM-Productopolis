<?php

namespace App\Filament\Resources\ReturnAlertResource\Pages;

use App\Filament\Resources\ReturnAlertResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReturnAlert extends EditRecord
{
    protected static string $resource = ReturnAlertResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
