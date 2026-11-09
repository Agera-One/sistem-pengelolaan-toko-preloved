<?php

namespace App\Services;

use App\Enums\DitanggungPengiriman;
use App\Enums\JenisPengiriman;
use App\Enums\KurirPengiriman;
use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use App\Models\Pengiriman;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PengirimanService
{
    public function index(array $query): array
    {
        return [
            'pengiriman' => Pengiriman::search($query['q'] ?? null)
                ->filter($query)
                ->dateBetween($query['tanggal_mulai'] ?? null, $query['tanggal_selesai'] ?? null)
                ->with('penjualan')
                ->latest('id')
                ->paginate(10)
                ->withQueryString(),
            'penjualan'     => Penjualan::orderBy('kode')->get(),
            'jenis'         => JenisPengiriman::cases(),
            'kurir'         => KurirPengiriman::cases(),
            'ditanggung'    => DitanggungPengiriman::cases(),
            'kurirDipilih'  => array_values(array_filter((array) request('kurir', []), 'is_scalar')),
        ];
    }

    public function create(): array
    {
        return [
            'penjualan'     => Penjualan::select('id', 'kode')->latest('id')->get(),
            'items'         => DetailPenjualan::with(['pakaian', 'pengiriman', 'penjualan'])->get(),
            'kurir'         => KurirPengiriman::cases(),
            'jenis'         => JenisPengiriman::cases(),
            'ditanggung'    => DitanggungPengiriman::cases(),
        ];
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
}
