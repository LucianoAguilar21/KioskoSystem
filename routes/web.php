<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Caja
    Route::prefix('cash-register')->name('cash-register.')->group(function () {
        Route::get('/', [CashRegisterController::class, 'index'])
            ->name('index');
        Route::post('/open', [CashRegisterController::class, 'open'])
            ->name('open');
        Route::get('/{session}/close', [CashRegisterController::class, 'showCloseForm'])
            ->name('close.form');
        Route::post('/{session}/close', [CashRegisterController::class, 'close'])
            ->name('close');
        Route::get('/{session}', [CashRegisterController::class, 'show'])
            ->name('show');
    });

    // Ventas
    // Ventas
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/', [SaleController::class, 'index'])
            ->name('index');
        Route::post('/', [SaleController::class, 'store'])
            ->name('store');
        Route::get('/history', [SaleController::class, 'history'])
            ->name('history');
        Route::get('/search', [SaleController::class, 'search'])
            ->name('search');
        Route::get('/{sale}', [SaleController::class, 'show'])
            ->name('show');
        Route::get('/{sale}/ticket', [SaleController::class, 'ticket'])
            ->name('ticket');
        Route::get('/{sale}/download-ticket', [SaleController::class, 'downloadTicket'])
            ->name('download-ticket');
    });

    // Productos
    Route::resource('products', ProductController::class);

    // Compras
    Route::resource('purchases', PurchaseController::class)
        ->only(['index', 'create', 'store', 'show']);

    // Proveedores
    Route::resource('suppliers', SupplierController::class)
        ->except(['destroy']);
});

require __DIR__.'/auth.php';