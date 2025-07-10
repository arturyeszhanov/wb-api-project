<?php

use Illuminate\Support\Facades\Route;
use App\Models\Sale;
use App\Models\Order;
use Illuminate\Http\Request;

use App\Http\Controllers\MainController;

Route::get('/', [MainController::class, 'index']);
Route::get('/sales', [MainController::class, 'sales'])->name('sales');
Route::get('/orders', [MainController::class, 'orders'])->name('orders');
Route::get('/stocks', [MainController::class, 'stocks'])->name('stocks');
Route::get('/incomes', [MainController::class, 'incomes'])->name('incomes');
