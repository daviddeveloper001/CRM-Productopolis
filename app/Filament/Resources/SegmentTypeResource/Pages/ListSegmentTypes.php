<?php

namespace App\Filament\Resources\SegmentTypeResource\Pages;

use App\Filament\Resources\SegmentTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSegmentTypes extends ListRecords
{
    protected static string $resource = SegmentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
