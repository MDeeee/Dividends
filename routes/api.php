<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apis\{
    DividendController,
    DashboardController
};

Route::prefix('dividends')->group(function () {
    Route::get('list',     [DividendController::class,'list']);
    Route::post('scrape',  [DividendController::class,'scrape']);
    Route::get('download', [DividendController::class,'download']);
    Route::post('delete',  [DividendController::class,'delete']);
});

Route::get('dashboard', [DashboardController::class,'list']);