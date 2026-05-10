<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MobileController;

Route::get('/', function () {
    return view('mobile');
});

Route::post('/calculate', [MobileController::class, 'calculateBill']);

Route::post('/api/mobile-bill', [MobileController::class, 'calculateBill']);
