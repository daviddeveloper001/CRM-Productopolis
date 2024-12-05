<?php

namespace App\Filament\Resources\SegmentationResource\Pages;

use App\Filament\Resources\SegmentationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSegmentations extends ListRecords
{
    protected static string $resource = SegmentationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
