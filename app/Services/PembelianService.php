<?php

namespace App\Services;

use App\Enums\BarangStatus;
use App\Models\Pembelian;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PembelianService
{
    private const FIELD_BARANG = ['nama', 'kategori', 'lingkar', 'panjang', 'harga_beli', 'harga_jual'];

    public function __construct(protected CodeGeneratorService $codeGenerator) {}

    public function create(array $data, int $userId): Pembelian
    {
        return DB::transaction(function () use ($data, $userId) {
            $items = collect($data['items'])->values();
            $kodeBarang = $this->codeGenerator->barang($items->count());

            $pembelian = Pembelian::create([
                'kode'        => $this->codeGenerator->pembelian(),
                'tanggal'     => $data['tanggal'],
                'total'       => $items->sum(fn ($r) => (int) $r['harga_beli']),
                'supplier_id' => $data['supplier_id'],
                'user_id'     => $userId,
            ]);

            foreach ($items as $i => $row) {
                $pembelian->barang()->create(
                    Arr::only($row, self::FIELD_BARANG) + [
                        'kode'   => $kodeBarang[$i],
                        'status' => BarangStatus::TERSEDIA,
                    ]
                );
            }

            return $pembelian;
        });
    }

    public function update(Pembelian $pembelian, array $data): Pembelian
    {
        return DB::transaction(function () use ($pembelian, $data) {
            $items    = collect($data['items'] ?? []);
            $locked   = $pembelian->barang()->where('status', '!=', BarangStatus::TERSEDIA)->get()->keyBy('id');
            $editable = $pembelian->barang()->where('status', BarangStatus::TERSEDIA)->get()->keyBy('id');

            $newCount = $items->filter(fn ($r) => blank($r['id'] ?? null))->count();
            $kodeBaru = $newCount > 0 ? $this->codeGenerator->barang($newCount) : [];
            $n = 0;
            $keptIds = [];

            foreach ($items as $row) {
                $attributes = Arr::only($row, self::FIELD_BARANG);

                if (blank($row['id'] ?? null)) {
                    $barang = $pembelian->barang()->create($attributes + [
                        'kode'   => $kodeBaru[$n++],
                        'status' => BarangStatus::TERSEDIA,
                    ]);
                    $keptIds[] = $barang->id;
                    continue;
                }

                $id = (int) $row['id'];

                if ($lockedBarang = $locked->get($id)) {
                    $lockedBarang->update(Arr::except($attributes, ['harga_beli']));
                    continue;
                }

                $barang = $editable->get($id)
                    ?? throw new \RuntimeException('Barang tidak valid.');

                $barang->update($attributes);
                $keptIds[] = $barang->id;
            }

            $editable->except($keptIds)->each->delete();

            $pembelian->update([
                'tanggal'     => $data['tanggal'],
                'supplier_id' => $data['supplier_id'],
                'total'       => $pembelian->barang()->sum('harga_beli'),
            ]);

            return $pembelian;
        });
    }
}
