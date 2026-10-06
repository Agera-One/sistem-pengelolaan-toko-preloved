<?php

namespace App\Observers;

use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\PembayaranPembelian;
use App\Models\PembayaranPenjualan;

class StatusObserver
{
    public function created($model): void
    {
        if ($model instanceof PembayaranPembelian) {
            $this->setStatus(Pembelian::class, $model->pembelian_id, 'Sudah Bayar');
        }

        if ($model instanceof PembayaranPenjualan) {
            $this->setStatus(Penjualan::class, $model->penjualan_id, 'Sudah Bayar');
        }
    }

    public function updated($model): void
    {
        if ($model instanceof PembayaranPembelian) {
            if ($model->wasChanged('pembelian_id')) {
                $lama_id = $model->getOriginal('pembelian_id');
                $ada = PembayaranPembelian::where('pembelian_id', $lama_id)->exists();
                $this->setStatus(Pembelian::class, $lama_id, $ada ? 'Sudah Bayar' : 'Belum Bayar');
            }

            $this->setStatus(Pembelian::class, $model->pembelian_id, 'Sudah Bayar');
        }

        if ($model instanceof PembayaranPenjualan) {
            if ($model->wasChanged('penjualan_id')) {
                $lama_id = $model->getOriginal('penjualan_id');
                $ada = PembayaranPenjualan::where('penjualan_id', $lama_id)->exists();
                $this->setStatus(Penjualan::class, $lama_id, $ada ? 'Sudah Bayar' : 'Belum Bayar');
            }

            $this->setStatus(Penjualan::class, $model->penjualan_id, 'Sudah Bayar');
        }
    }

    public function deleted($model): void
    {
        if ($model instanceof PembayaranPembelian) {
            $ada = PembayaranPembelian::where('pembelian_id', $model->pembelian_id)->exists();
            $this->setStatus(Pembelian::class, $model->pembelian_id, $ada ? 'Sudah Bayar' : 'Belum Bayar');
        }

        if ($model instanceof PembayaranPenjualan) {
            $ada = PembayaranPenjualan::where('penjualan_id', $model->penjualan_id)->exists();
            $this->setStatus(Penjualan::class, $model->penjualan_id, $ada ? 'Sudah Bayar' : 'Belum Bayar');
        }
    }

    private function setStatus(string $model_class, $id, string $status): void
    {
        $model_class::where('id', $id)->update([
            'status'     => $status,
            'updated_at' => now(),
        ]);
    }
}
