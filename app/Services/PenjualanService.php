<?php

namespace App\Services;

use App\Models\Penjualan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PenjualanService
{
    public function __construct(
        protected CodeGeneratorService $codeGenerator,
    ) {}

    public function create(array $data, int $userId): Penjualan
    {
        return DB::transaction(function () use ($data, $userId) {
            $items  = collect($data['items']);
            $totals = $this->hitungTotal($items, $data['ongkir']);

            $penjualan = Penjualan::forceCreate([
                'tanggal'      => $data['tanggal'],
                'kode'         => $this->codeGenerator->penjualan(),
                'pelanggan_id' => $data['pelanggan_id'],
                'user_id'      => $userId,
            ] + $totals);

            $this->simpanDetail($penjualan, $items);

            return $penjualan;
        });
    }

    public function update(Penjualan $penjualan, array $data): Penjualan
    {
        return DB::transaction(function () use ($penjualan, $data) {
            $items  = collect($data['items']);
            $totals = $this->hitungTotal($items, $data['ongkir']);

            $penjualan->forceFill([
                'tanggal'      => $data['tanggal'],
                'pelanggan_id' => $data['pelanggan_id'],
            ] + $totals)->save();

            DB::table('detail_penjualan')
                ->where('penjualan_id', $penjualan->getKey())
                ->delete();

            $this->simpanDetail($penjualan, $items);

            return $penjualan;
        });
    }

    protected function hitungTotal(Collection $items, $ongkir): array
    {
        $subtotal = $items->sum('harga_jual');
        $ongkir   = (int) $ongkir;

        return [
            'subtotal' => $subtotal,
            'ongkir'   => $ongkir,
            'total'    => $subtotal + $ongkir,
        ];
    }

    protected function simpanDetail(Penjualan $penjualan, Collection $items): void
    {
        DB::table('detail_penjualan')->insert(
            $items->map(fn ($item) => [
                'penjualan_id' => $penjualan->getKey(),
                'barang_id'    => $item['barang_id'],
                'harga_jual'   => $item['harga_jual'],
            ])->all()
        );
    }
}
