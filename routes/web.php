<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PakaianController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PembayaranPembelianController;
use App\Http\Controllers\PembayaranPenjualanController;

Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/', [LoginController::class, 'attempt'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => view('pages.dashboard'))->name('dashboard');

    Route::resource('pakaian', PakaianController::class)->except(['show', 'create', 'store', 'edit']);
    Route::resource('pelanggan', PelangganController::class)->except(['show', 'create', 'edit']);
    Route::resource('supplier', SupplierController::class)->except(['show', 'create', 'edit']);

    Route::resource('pembelian', PembelianController::class);
    Route::resource('pembayaran-pembelian', PembayaranPembelianController::class)
        ->except(['show', 'create', 'edit'])
        ->names('pembayaran-pembelian');

    Route::resource('penjualan', PenjualanController::class);
    Route::resource('pembayaran-penjualan', PembayaranPenjualanController::class)
        ->except(['show', 'create', 'edit'])
        ->names('pembayaran-penjualan');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
