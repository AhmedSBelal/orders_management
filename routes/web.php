<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\ExpenseCategoryController;

Route::controller(\App\Http\Controllers\AuthController::class)->group(function () {

    Route::get('/', 'loginShow')->name('login.show');
    Route::post('login', 'login')
        ->middleware('throttle:5,1') // Allow 5 attempts per minute to prevent brute-force attacks
        ->name('login.post');
    Route::get('register', 'registerShow')->name('register.show');
    Route::post('register', 'register')
        ->middleware('throttle:5,1')
        ->name('register.post');

});



Route::middleware(['auth'])->group(function () {

    // Auth
    Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

    // welcome
    Route::get('/welcome', [HomeController::class, 'welcome'])->name('welcome');

    // orders
    Route::resource('orders/statuses', OrderStatusController::class, ['names' => 'orders.statuses']);
    Route::resource('orders', OrderController::class);
    Route::post('orders/bulk-update-status', [OrderController::class, 'bulkUpdateStatus'])->name('orders.bulk-update-status');
    Route::get('in-processing', [HomeController::class, 'inProcessing'])->name('in-processing');
    Route::post('orders/search', [OrderController::class, 'search'])->name('orders.search');
    

    Route::resource('products', ProductController::class);
    Route::delete('/products/{product}/images/{image}', [ProductController::class, 'deleteImage'])->name('products.images.delete');

    Route::resource('colors', \App\Http\Controllers\ColorController::class);


    Route::resource('expenses', ExpenseController::class);
    Route::resource('expense-categories', ExpenseCategoryController::class);

    Route::get('reports/profit-loss', [ReportController::class, 'profitLoss'])
    ->name('reports.profit-loss');

    Route::get('/reports/export/{format}', [ReportController::class, 'exportReport'])->name('reports.export');


});
