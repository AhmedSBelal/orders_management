<?php

use Illuminate\Support\Facades\Route;

Route::controller(\App\Http\Controllers\AuthController::class)->group(function () {

    Route::get('login', 'loginShow')->name('login.show');
    Route::post('login', 'login')
        ->middleware('throttle:5,1') // Allow 5 attempts per minute to prevent brute-force attacks
        ->name('login.post');
    Route::get('register', 'registerShow')->name('register.show');
    Route::post('register', 'register')
        ->middleware('throttle:5,1')
        ->name('register.post');

});



Route::middleware(['auth'])->group(function () {

    Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

    Route::resource('orders', \App\Http\Controllers\OrderController::class);

    Route::get('products', function () {
        return 'here';
    })->name('products');

    Route::get('colors', function () {return 'hree';})->name('colors');

});
