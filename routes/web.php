<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

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

    // orders
    Route::resource('orders', \App\Http\Controllers\OrderController::class);
    Route::post('orders/bulk-update-status', [\App\Http\Controllers\OrderController::class, 'bulkUpdateStatus'])->name('orders.bulk-update-status');
    Route::get('in-processing', [HomeController::class, 'inProcessing'])->name('in-processing');

    Route::resource('products', \App\Http\Controllers\ProductController::class);

    Route::resource('colors', \App\Http\Controllers\ColorController::class);

});
