<?php

namespace App\Services;

use App\Enums\MetodePembayaran;
use App\Enums\PenjualanStatus;
use App\Models\PembayaranPenjualan;
use App\Models\Penjualan;

class PembayaranPenjualanService
{
    public function __construct(
        protected CodeGeneratorService $codeGenerator,
    ) {}

    public function index(array $query): array
    {
        return [
            'pembayaran' => PembayaranPenjualan::denganRelasi()
                ->search($query['q'] ?? null)
                ->filter($query)
                ->dateBetween($query['tanggal_mulai'] ?? null, $query['tanggal_selesai'] ?? null)
                ->latest('id')
                ->paginate(10)
                ->withQueryString(),
            'penjualan' => Penjualan::where('status', PenjualanStatus::BELUM_BAYAR)
                ->with('pelanggan')
                ->latest('id')
                ->get(),
            'kode'   => $this->codeGenerator->pembayaranPenjualan(),
            'metode' => MetodePembayaran::cases(),
        ];
    }


    public function store(array $validated)
    {
        $penjualan = Penjualan::findOrFail($validated['penjualan_id']);
        $validated['kode'] = $this->codeGenerator->pembayaranPenjualan();
        $validated['nominal'] = $penjualan->total;
        PembayaranPenjualan::create($validated);
    }

    public function update(array $validated, PembayaranPenjualan $pembayaranPenjualan)
    {
        $validated['nominal'] = Penjualan::findOrFail($validated['penjualan_id'])->total;
        $pembayaranPenjualan->update($validated);
    }
}
