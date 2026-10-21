<?php

namespace App\Providers;

use App\Models\DetailPenjualan;
use App\Models\PembayaranPembelian;
use App\Models\PembayaranPenjualan;
use App\Observers\DetailPenjualanObserver;
use App\Observers\PembayaranObserver;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.custom');

        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.utf8', 'id_ID', 'id');

        DetailPenjualan::observe(DetailPenjualanObserver::class);
        PembayaranPembelian::observe(PembayaranObserver::class);
        PembayaranPenjualan::observe(PembayaranObserver::class);
    }
}
