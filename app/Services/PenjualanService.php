<?php

namespace App\Services;

use App\Enums\BarangStatus;
use App\Enums\PenjualanStatus;
use App\Models\Barang;
use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PenjualanService
{
    public function __construct(
        protected CodeGeneratorService $codeGenerator,
    ) {}

    public function index(array $query): array
    {
        return [
            'penjualan' => Penjualan::search($query['q'] ?? null)
                ->filter($query)
                ->dateBetween($query['tanggal_mulai'] ?? null, $query['tanggal_selesai'] ?? null)
                ->with(['pelanggan', 'user'])
                ->latest('id')
                ->paginate(10)
                ->withQueryString(),
            'pelanggan' => Pelanggan::orderBy('nama')->get(),
            'statuses'  => PenjualanStatus::cases(),
        ];
    }

    public function create(): array
    {
        $barang = Barang::where('status', BarangStatus::TERSEDIA)
            ->latest('id')
            ->get();

        return array_merge($this->dataForm($barang), [
            'kode'      => $this->codeGenerator->penjualan(),
            'pelanggan' => Pelanggan::orderBy('nama')->get(),
        ]);
    }


    public function store(array $data, int $userId): Penjualan
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

    public function edit(Penjualan $penjualan): array
    {
        $barang = Barang::where('status', BarangStatus::TERSEDIA)
            ->orWhereIn('id', $penjualan->barang()->pluck('barang.id'))
            ->latest('id')
            ->get();

        $items = DB::table('detail_penjualan')
            ->where('penjualan_id', $penjualan->getKey())
            ->get(['barang_id', 'harga_jual'])
            ->map(fn ($row) => [
                'barang_id'  => $row->barang_id,
                'harga_jual' => (int) $row->harga_jual,
            ])
            ->all();

        $ongkir = (int) $penjualan->ongkir;

        return array_merge($this->dataForm($barang, $items), [
            'penjualan'   => $penjualan,
            'pelanggan'   => Pelanggan::orderBy('nama')->get(),
            'tanggal'     => Carbon::parse($penjualan->tanggal)->toDateString(),
            'pelangganId' => $penjualan->pelanggan_id,
            'ongkirAwal'  => $ongkir > 0 ? number_format($ongkir, 0, ',', '.') : '',
        ]);
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

            DetailPenjualan::where('penjualan_id', $penjualan->getKey())
                ->get()
                ->each->delete();

            $this->simpanDetail($penjualan, $items);

            return $penjualan;
        });
    }

    public function destroy(Penjualan $penjualan)
    {
        DB::transaction(function () use ($penjualan) {
            $penjualan->detailPenjualan->each->delete();
            $penjualan->delete();
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
        foreach ($items as $item) {
            DetailPenjualan::create([
                'penjualan_id' => $penjualan->getKey(),
                'barang_id'    => $item['barang_id'],
                'harga_jual'   => $item['harga_jual'],
            ]);
        }
    }

        protected function dataForm(Collection $barang, array $items = []): array
    {
        $angka      = fn ($v) => $v === null || $v === '' ? '' : (float) $v;
        $barangById = $barang->keyBy(fn ($b) => $b->getKey());

        $buatBaris = function ($index, array $item, bool $template = false) use ($barangById, $angka): array {
            $barangId = $item['barang_id'] ?? null;
            $selected = filled($barangId) ? $barangById->get($barangId) : null;

            $harga = $item['harga_jual'] ?? null;
            if (blank($harga) && $selected) {
                $harga = (int) $selected->harga_jual;
            }

            return [
                'index'     => $index,
                'template'  => $template,
                'barang_id' => $barangId,
                'kategori'  => $selected?->kategori?->value,
                'lingkar'   => $selected ? $angka($selected->lingkar) : '',
                'panjang'   => $selected ? $angka($selected->panjang) : '',
                'harga'     => blank($harga)
                    ? ''
                    : number_format((int) preg_replace('/\D/', '', (string) $harga), 0, ',', '.'),
            ];
        };

        $sumber = old('items') ?: $items;
        $sumber = $sumber ?: [[]];

        $rows = [];
        foreach ($sumber as $i => $item) {
            $rows[] = $buatBaris($i, (array) $item);
        }
        $rows[] = $buatBaris('__INDEX__', [], true);

        return [
            'rows'          => $rows,
            'barangOptions' => $barang->map(fn ($b) => [
                'id'       => $b->getKey(),
                'label'    => "{$b->kode} — {$b->nama}",
                'kategori' => $b->kategori?->value,
                'lingkar'  => $angka($b->lingkar),
                'panjang'  => $angka($b->panjang),
                'harga'    => (int) $b->harga_jual,
            ])->values()->all(),
            'tanggal'       => now()->toDateString(),
            'pelangganId'   => null,
            'ongkirAwal'    => '',
        ];
    }

}
