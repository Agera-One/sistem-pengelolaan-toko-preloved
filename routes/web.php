<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PembelianControler;
use App\Http\Controllers\PembayaranPembelianControler;

Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/', [LoginController::class, 'attempt'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => view('pages.dashboard'))->name('dashboard');

    Route::resource('barang', BarangController::class)->except(['show', 'create', 'store', 'edit']);
    Route::resource('pelanggan', PelangganController::class)->except(['show', 'create', 'edit']);
    Route::resource('supplier', SupplierController::class)->except(['show', 'create', 'edit']);

    Route::resource('pembelian', PembelianControler::class);
    Route::resource('pembelian/pembayaran', PembayaranPembelianControler::class)->except(['show', 'create', 'edit'])->names('pembelian.pembayaran');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
