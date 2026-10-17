<?php

namespace App\Observers;

use App\Enums\PembelianStatus;
use App\Enums\PenjualanStatus;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\PembayaranPembelian;
use App\Models\PembayaranPenjualan;

class PembayaranObserver
{
    public function created($model): void
    {
        if ($model instanceof PembayaranPembelian) {
            $this->setStatus(Pembelian::class, $model->pembelian_id, PembelianStatus::SUDAH_BAYAR);
        }

        if ($model instanceof PembayaranPenjualan) {
            $this->setStatus(Penjualan::class, $model->penjualan_id, PenjualanStatus::SUDAH_BAYAR);
        }
    }

    public function updated($model): void
    {
        if ($model instanceof PembayaranPembelian) {
            if ($model->wasChanged('pembelian_id')) {
                $lama_id = $model->getOriginal('pembelian_id');
                $ada = PembayaranPembelian::where('pembelian_id', $lama_id)->exists();
                $this->setStatus(Pembelian::class, $lama_id, $ada ? PembelianStatus::SUDAH_BAYAR : PembelianStatus::BELUM_BAYAR);
            }

            $this->setStatus(Pembelian::class, $model->pembelian_id, PembelianStatus::SUDAH_BAYAR);
        }

        if ($model instanceof PembayaranPenjualan) {
            if ($model->wasChanged('penjualan_id')) {
                $lama_id = $model->getOriginal('penjualan_id');
                $ada = PembayaranPenjualan::where('penjualan_id', $lama_id)->exists();
                $this->setStatus(Penjualan::class, $lama_id, $ada ? PenjualanStatus::SUDAH_BAYAR : PenjualanStatus::BELUM_BAYAR);
            }

            $this->setStatus(Penjualan::class, $model->penjualan_id, PenjualanStatus::SUDAH_BAYAR);
        }
    }

    public function deleted($model): void
    {
        if ($model instanceof PembayaranPembelian) {
            $ada = PembayaranPembelian::where('pembelian_id', $model->pembelian_id)->exists();
            $this->setStatus(Pembelian::class, $model->pembelian_id, $ada ? PembelianStatus::SUDAH_BAYAR : PembelianStatus::BELUM_BAYAR);
        }

        if ($model instanceof PembayaranPenjualan) {
            $ada = PembayaranPenjualan::where('penjualan_id', $model->penjualan_id)->exists();
            $this->setStatus(Penjualan::class, $model->penjualan_id, $ada ? PenjualanStatus::SUDAH_BAYAR : PenjualanStatus::BELUM_BAYAR);
        }
    }

    private function setStatus(string $model, $id, $status): void
    {
        $model::where('id', $id)->update([
            'status'     => $status,
            'updated_at' => now(),
        ]);
    }
}
