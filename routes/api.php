<?php

use App\Http\Controllers\Api\V1\BlockController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\SaleController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('v1/sales', SaleController::class);

Route::get('v1/blocks', [BlockController::class, 'index'])->name('blocks.index');