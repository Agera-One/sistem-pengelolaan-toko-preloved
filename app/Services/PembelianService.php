<?php

namespace App\Services;

use App\Enums\PakaianStatus;
use App\Enums\KategoriPakaian;
use App\Enums\PembelianStatus;
use App\Models\Pakaian;
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
            'kategori'  => KategoriPakaian::cases(),
        ];
    }

    public function store(array $data, int $userId): Pembelian
    {
        return DB::transaction(function () use ($data, $userId) {
            $items = collect($data['items'])->values();
            $kodePakaian = $this->codeGenerator->pakaian($items->count());

            $pembelian = Pembelian::create([
                'kode'        => $this->codeGenerator->pembelian(),
                'tanggal'     => $data['tanggal'],
                'total'       => $items->sum(fn ($r) => (int) $r['harga_beli']),
                'supplier_id' => $data['supplier_id'],
                'user_id'     => $userId,
            ]);

            $pakaianData = [];

            foreach ($items as $i => $row) {
                $pakaianData[] = Arr::only($row, self::FIELD_BARANG) + [
                    'pembelian_id' => $pembelian->id,
                    'kode'         => $kodePakaian[$i],
                    'status'       => PakaianStatus::TERSEDIA->value,
                ];
            }

            Pakaian::insert($pakaianData);

            return $pembelian;
        });
    }

    public function edit(Pembelian $pembelian): array
    {
        $lockedIds = $this->lockedPakaianIds($pembelian);

        $rows = old('items') ?: $pembelian->pakaian
            ->map(fn (Pakaian $pakaian) => $this->toFormRow($pakaian))
            ->all();

        $rows = collect($rows)
            ->map(fn (array $row) => $row + [
                'locked' => ! empty($row['id']) && in_array($row['id'], $lockedIds),
            ])
            ->all();

        return [
            'pembelian'  => $pembelian,
            'supplier'   => Supplier::orderBy('nama')->get(),
            'kategori'   => KategoriPakaian::cases(),
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
            $locked   = $pembelian->pakaian()->where('status', '!=', PakaianStatus::TERSEDIA)->get()->keyBy('id');
            $editable = $pembelian->pakaian()->where('status', PakaianStatus::TERSEDIA)->get()->keyBy('id');

            $newCount = $items->filter(fn ($r) => blank($r['id'] ?? null))->count();
            $kodeBaru = $newCount > 0 ? $this->codeGenerator->pakaian($newCount) : [];
            $n = 0;
            $keptIds = [];

            foreach ($items as $row) {
                $attributes = Arr::only($row, self::FIELD_BARANG);

                if (blank($row['id'] ?? null)) {
                    $pakaian = $pembelian->pakaian()->create($attributes + [
                        'kode'   => $kodeBaru[$n++],
                        'status' => PakaianStatus::TERSEDIA,
                    ]);
                    $keptIds[] = $pakaian->id;
                    continue;
                }

                $id = (int) $row['id'];

                if ($lockedPakaian = $locked->get($id)) {
                    $lockedPakaian->update(Arr::except($attributes, ['harga_beli']));
                    continue;
                }

                $pakaian = $editable->get($id)
                    ?? throw new \RuntimeException('Pakaian tidak valid.');

                $pakaian->update($attributes);
                $keptIds[] = $pakaian->id;
            }

            $idsToDelete = $editable->except($keptIds)->pluck('id');
            if ($idsToDelete->isNotEmpty()) {
                Pakaian::whereIn('id', $idsToDelete)->delete();
            }

            $pembelian->update([
                'tanggal'     => $data['tanggal'],
                'supplier_id' => $data['supplier_id'],
                'total'       => $pembelian->pakaian()->sum('harga_beli'),
            ]);

            return $pembelian;
        });
    }

    private function lockedPakaianIds(Pembelian $pembelian): array
    {
        return $pembelian->pakaian
            ->where('status', '!=', PakaianStatus::TERSEDIA)
            ->pluck('id')
            ->all();
    }

    private function toFormRow(Pakaian $pakaian): array
    {
        return [
            'id'         => $pakaian->id,
            'nama'       => $pakaian->nama,
            'kategori'   => $pakaian->kategori->value,
            'lingkar'    => (float) $pakaian->lingkar,
            'panjang'    => (float) $pakaian->panjang,
            'harga_beli' => (int) $pakaian->harga_beli,
            'harga_jual' => (int) $pakaian->harga_jual,
        ];
    }
}
