<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MobileController;

Route::post('/mobile-bill', [MobileController::class, 'calculateBill']);
