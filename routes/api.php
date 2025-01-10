<?php

use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPriceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('products', ProductController::class);
Route::get('products/{id}/prices', [ProductPriceController::class, 'index']);
Route::post('products/{id}/prices', [ProductPriceController::class, 'store']);
Route::apiResource('currencies', CurrencyController::class);
