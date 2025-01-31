<?php

use Illuminate\Support\Facades\Route;

Route::get('login', [\App\Http\Controllers\AuthController::class, 'loginShow'])->name('login.show');
Route::post('login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login.post');
Route::get('register', [\App\Http\Controllers\AuthController::class, 'registerShow'])->name('register.show');
Route::post('register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register.post');

Route::middleware(['auth'])->group(function () {

    Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
    Route::get('/', function () {
        return view('Dashboard.index');
    })->name('dashboard');

});
