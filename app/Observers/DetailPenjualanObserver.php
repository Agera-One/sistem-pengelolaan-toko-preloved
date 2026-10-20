<?php

namespace App\Observers;

use App\Enums\BarangStatus;
use App\Models\Barang;
use App\Models\DetailPenjualan;

class DetailPenjualanObserver
{
    public function created(DetailPenjualan $detailPenjualan): void
    {
        $detailPenjualan->barang()->update([
            'status' => BarangStatus::TERJUAL
        ]);
    }

    public function updated(DetailPenjualan $detailPenjualan): void
    {
        if ($detailPenjualan->wasChanged('barang_id')) {
            $barangLamaId = $detailPenjualan->getOriginal('barang_id');

            if ($barangLamaId) {
                Barang::where('id', $barangLamaId)->update([
                    'status' => 'tersedia',
                ]);
            }

            $detailPenjualan->barang->update([
                'status' => 'terjual',
            ]);
        }
    }

    public function deleted(DetailPenjualan $detailPenjualan): void
    {
        $detailPenjualan->barang()->update([
            'status' => BarangStatus::TERSEDIA
        ]);
    }
}
