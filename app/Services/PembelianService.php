<?php

namespace App\Services;

use App\Enums\BarangStatus;
use App\Enums\KategoriBarang;
use App\Enums\PembelianStatus;
use App\Models\Barang;
use App\Models\Pembelian;
use App\Models\Supplier;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PembelianService
{
    private const FIELD_BARANG = ['nama', 'kategori', 'lingkar', 'panjang', 'harga_beli', 'harga_jual'];

    public function __construct(protected CodeGeneratorService $codeGenerator) {}

    public function index(array $query): array
    {
        return [
            'pembelian' => Pembelian::search($query['q'] ?? null)
                ->filter($query)
                ->dateBetween($query['tanggal_mulai'] ?? null, $query['tanggal_selesai'] ?? null)
                ->with(['supplier', 'user'])
                ->latest('id')
                ->paginate(10)
                ->withQueryString(),
            'supplier'  => Supplier::orderBy('nama')->get(),
            'statuses'  => PembelianStatus::cases(),
        ];
    }

    public function create(): array
    {
        return [
            'kode'      => $this->codeGenerator->pembelian(),
            'supplier'  => Supplier::orderBy('nama')->get(),
            'kategori'  => KategoriBarang::cases(),
        ];
    }

    public function store(array $data, int $userId): Pembelian
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

    public function edit(Pembelian $pembelian): array
    {
        $lockedIds = $this->lockedBarangIds($pembelian);

        $rows = old('items') ?: $pembelian->barang
            ->map(fn (Barang $barang) => $this->toFormRow($barang))
            ->all();

        $rows = collect($rows)
            ->map(fn (array $row) => $row + [
                'locked' => ! empty($row['id']) && in_array($row['id'], $lockedIds),
            ])
            ->all();

        return [
            'pembelian'  => $pembelian,
            'supplier'   => Supplier::orderBy('nama')->get(),
            'kategori'   => KategoriBarang::cases(),
            'lockedIds'  => $lockedIds,
            'rows'       => $rows ?: [['locked' => false]],
            'tanggal'    => old('tanggal', Carbon::parse($pembelian->tanggal)->format('Y-m-d')),
            'supplierId' => old('supplier_id', $pembelian->supplier_id),
        ];
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

    private function lockedBarangIds(Pembelian $pembelian): array
    {
        return $pembelian->barang
            ->where('status', '!=', BarangStatus::TERSEDIA)
            ->pluck('id')
            ->all();
    }

    private function toFormRow(Barang $barang): array
    {
        return [
            'id'         => $barang->id,
            'nama'       => $barang->nama,
            'kategori'   => $barang->kategori->value,
            'lingkar'    => (float) $barang->lingkar,
            'panjang'    => (float) $barang->panjang,
            'harga_beli' => (int) $barang->harga_beli,
            'harga_jual' => (int) $barang->harga_jual,
        ];
    }
}
