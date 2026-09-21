<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PelangganController;

Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/', [LoginController::class, 'attempt'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => view('pages.dashboard'))->name('dashboard');
    Route::resource('pelanggan', PelangganController::class)->except(['create', 'show']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
