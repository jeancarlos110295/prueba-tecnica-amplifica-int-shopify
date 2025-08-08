<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopifyController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class , 'dashboard'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::controller(ShopifyController::class)->prefix('shopify')->group(function () {
        Route::get('install', 'install')->name('shopify.install');

        Route::get('callback', 'handleCallback')->name('shopify.callback');

        Route::get('productos', 'productos')->name('shopify.productos');
        Route::get('/export/productos.xlsx', 'exportProductosExcel')->name('export.productos.xlsx');

        Route::get('pedidos', 'pedidos')->name('shopify.pedidos');
    });
});

require __DIR__.'/auth.php';
