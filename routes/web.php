<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Pages\ReportViewPage;
use App\Filament\Pages\TableSegmentionsPage;

Route::get('/table-segmentations', TableSegmentionsPage::class)->name('table-segmentations');
Route::get('/report-view/{record}', ReportViewPage::class)->name('filament.pages.report-view');
