<?php

namespace App\Services;

use App\Enums\DitanggungPengiriman;
use App\Enums\JenisPengiriman;
use App\Enums\KurirPengiriman;
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
}
