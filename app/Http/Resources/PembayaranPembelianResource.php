<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Model;

class PembayaranPembelianResource extends PembayaranResource
{
    protected function relasi(): string
    {
        return 'pembelian';
    }

    protected function routePrefix(): string
    {
        return 'pembayaran-pembelian';
    }

    protected function keyPihak(): string
    {
        return 'supplier';
    }

    protected function namaPihak(Model $transaksi): ?string
    {
        return $transaksi->supplier?->nama;
    }
}
