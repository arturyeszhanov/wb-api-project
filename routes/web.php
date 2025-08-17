<?php

use Illuminate\Support\Facades\Route;
use App\Models\Sale;
use App\Models\Order;
use Illuminate\Http\Request;

use App\Http\Controllers\MainController;

use App\Http\Controllers\ProxyController;

Route::get('/api/orders', [ProxyController::class, 'orders']);
Route::get('/api/sales', [ProxyController::class, 'sales']);
Route::get('/api/stocks', [ProxyController::class, 'stocks']);
Route::get('/api/incomes', [ProxyController::class, 'incomes']);



Route::get('/{any}', function () {
    return view('index'); // предполагается, что это Blade-шаблон, который содержит <div id="app"></div> и подключает Vue SPA
})->where('any', '.*');
