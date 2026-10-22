<?php

namespace App\Observers;

use App\Enums\PakaianStatus;
use App\Models\Pakaian;
use App\Models\DetailPenjualan;

class DetailPenjualanObserver
{
    public function created(DetailPenjualan $detailPenjualan): void
    {
        $detailPenjualan->pakaian()->update([
            'status' => PakaianStatus::TERJUAL
        ]);
    }

    public function updated(DetailPenjualan $detailPenjualan): void
    {
        if ($detailPenjualan->wasChanged('pakaian_id')) {
            $pakaianLamaId = $detailPenjualan->getOriginal('pakaian_id');

            if ($pakaianLamaId) {
                Pakaian::where('id', $pakaianLamaId)->update([
                    'status' => 'tersedia',
                ]);
            }

            $detailPenjualan->pakaian->update([
                'status' => 'terjual',
            ]);
        }
    }

    public function deleted(DetailPenjualan $detailPenjualan): void
    {
        $detailPenjualan->pakaian()->update([
            'status' => PakaianStatus::TERSEDIA
        ]);
    }
}
