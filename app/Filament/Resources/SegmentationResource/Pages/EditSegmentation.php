<?php

namespace App\Filament\Resources\SegmentationResource\Pages;

use App\Filament\Resources\SegmentationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSegmentation extends EditRecord
{
    protected static string $resource = SegmentationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
