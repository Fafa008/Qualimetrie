<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\MobileController;

Route::get('/', function () {
    return view('mobile');
});
Route::post('/calculate', function (Request $request) {
    $controller = new MobileController();
    $response = $controller->calculateBill($request);
    $data = $response->getData();

    return view('mobile', ['total' => $data->total]);
});
