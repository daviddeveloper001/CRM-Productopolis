<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\CrmController;
use App\Http\Controllers\Api\V1\SaleController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('v1/crm', CrmController::class);
Route::apiResource('v1/sales', SaleController::class);