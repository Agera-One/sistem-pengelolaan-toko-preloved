<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Model;

class PembayaranPenjualanResource extends PembayaranResource
{
    protected function relasi(): string
    {
        return 'penjualan';
    }

    protected function routePrefix(): string
    {
        return 'pembayaran-penjualan';
    }

    protected function keyPihak(): string
    {
        return 'pelanggan';
    }

    protected function namaPihak(Model $transaksi): ?string
    {
        return $transaksi->pelanggan?->nama;
    }
}
