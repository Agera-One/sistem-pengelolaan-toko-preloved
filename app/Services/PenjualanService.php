<?php

namespace App\Services;

use App\Enums\PakaianStatus;
use App\Enums\PenjualanStatus;
use App\Models\Pakaian;
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
        $pakaian = Pakaian::where('status', PakaianStatus::TERSEDIA)
            ->latest('id')
            ->get();

        return array_merge($this->dataForm($pakaian), [
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
        $pakaian = Pakaian::where('status', PakaianStatus::TERSEDIA)
            ->orWhereIn('id', $penjualan->pakaian()->pluck('pakaian.id'))
            ->latest('id')
            ->get();

        $items = DB::table('detail_penjualan')
            ->where('penjualan_id', $penjualan->getKey())
            ->get(['pakaian_id', 'harga_jual'])
            ->map(fn ($row) => [
                'pakaian_id'  => $row->pakaian_id,
                'harga_jual' => (int) $row->harga_jual,
            ])
            ->all();

        $ongkir = (int) $penjualan->ongkir;

        return array_merge($this->dataForm($pakaian, $items), [
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

            DetailPenjualan::where('penjualan_id', $penjualan->getKey())->delete();

            $this->simpanDetail($penjualan, $items);

            return $penjualan;
        });
    }

    public function destroy(Penjualan $penjualan)
    {
        DB::transaction(function () use ($penjualan) {
            $penjualan->detailPenjualan()->delete();
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
        $detailData = $items->map(fn ($item) => [
            'penjualan_id' => $penjualan->getKey(),
            'pakaian_id'   => $item['pakaian_id'],
            'harga_jual'   => $item['harga_jual'],
        ])->toArray();

        DetailPenjualan::insert($detailData);
    }

    protected function dataForm(Collection $pakaian, array $items = []): array
    {
        $angka      = fn ($v) => $v === null || $v === '' ? '' : (float) $v;
        $pakaianById = $pakaian->keyBy(fn ($b) => $b->getKey());

        $buatBaris = function ($index, array $item, bool $template = false) use ($pakaianById, $angka): array {
            $pakaianId = $item['pakaian_id'] ?? null;
            $selected = filled($pakaianId) ? $pakaianById->get($pakaianId) : null;

            $harga = $item['harga_jual'] ?? null;
            if (blank($harga) && $selected) {
                $harga = (int) $selected->harga_jual;
            }

            return [
                'index'      => $index,
                'template'   => $template,
                'pakaian_id' => $pakaianId,
                'kategori'   => $selected?->kategori?->value,
                'lingkar'    => $selected ? $angka($selected->lingkar) : '',
                'panjang'    => $selected ? $angka($selected->panjang) : '',
                'harga_beli' => $selected ? (int) $selected->harga_beli : null,
                'harga_jual' => blank($harga)
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
            'rows'           => $rows,
            'pakaianOptions' => $pakaian->map(fn ($b) => [
                'id'         => $b->getKey(),
                'label'      => "{$b->kode} — {$b->nama}",
                'kategori'   => $b->kategori?->value,
                'lingkar'    => $angka($b->lingkar),
                'panjang'    => $angka($b->panjang),
                'harga_jual' => (int) $b->harga_jual,
                'harga_beli' => (int) $b->harga_beli,
            ])->values()->all(),
            'tanggal'     => now()->toDateString(),
            'pelangganId' => null,
            'ongkirAwal'  => '',
        ];
    }
}
