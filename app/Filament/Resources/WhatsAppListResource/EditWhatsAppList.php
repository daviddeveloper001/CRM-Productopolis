<?php

namespace App\Filament\Resources\ShopResource\Pages;

use App\Filament\Resources\WhatsAppListResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWhatsAppList extends EditRecord
{
    protected static string $resource = WhatsAppListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
