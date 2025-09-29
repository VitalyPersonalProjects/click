<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClickController;

Route::post('/webhook', [ClickController::class, 'store']);   // приём кликов
Route::get('/report', [ClickController::class, 'report']);    // статистика
Route::post('/forward', [ClickController::class, 'forward']); // экспорт
