<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\Supplier;
use App\Models\Pelanggan;
use App\Models\Pembelian;
use App\Models\PembayaranPembelian;
use App\Models\Penjualan;
use Illuminate\Database\Eloquent\Model;

class CodeGeneratorService
{
    public function generate(Model $model, string $column, string $prefix): string
    {
        return $this->generate_batch($model, $column, $prefix, 1)[0];
    }

    public function generate_batch(Model $model, string $column, string $prefix, int $count): array
    {
        if ($count < 1) {
            return [];
        }

        $period = now()->format('Ym');

        $last = $model->newQuery()
            ->where($column, 'like', "{$prefix}-{$period}-%")
            ->orderByDesc($column)
            ->lockForUpdate()
            ->value($column);

        $start = $last ? ((int) substr($last, -4)) + 1 : 1;

        return array_map(
            fn (int $number) => sprintf('%s-%s-%04d', $prefix, $period, $number),
            range($start, $start + $count - 1)
        );
    }

    public function barang(int $count) {
        return $this->generate_batch(new Barang(), 'kode', 'BRG', $count);
    }

    public function supplier()
    {
        return $this->generate(new Supplier(), 'kode', 'SPL'
        );
    }

    public function pelanggan()
    {
        return $this->generate(new Pelanggan(), 'kode', 'PLG');
    }

    public function pembelian()
    {
        return $this->generate(new Pembelian(), 'kode', 'BEL');
    }

    public function pembayaran_pembelian()
    {
        return $this->generate(new PembayaranPembelian(), 'kode', 'KLR');
    }

    public function penjualan()
    {
        return $this->generate(new penjualan(), 'kode', 'JUA');
    }
}
