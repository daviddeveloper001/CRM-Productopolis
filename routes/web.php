<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Pages\TableSegmentionsPage;

Route::get('/table-segmentations', TableSegmentionsPage::class)->name('table-segmentations');
