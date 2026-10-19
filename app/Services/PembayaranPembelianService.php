<?php

namespace App\Services;

use App\Enums\MetodePembayaran;
use App\Models\PembayaranPembelian;
use App\Models\Pembelian;

class PembayaranPembelianService
{
    public function __construct(
        protected CodeGeneratorService $codeGenerator,
    ) {}

    public function index(array $query): array
    {
        return [
            'pembayaran'    => PembayaranPembelian::search($query['q'] ?? null)
                ->latest('id')
                ->paginate(10)
                ->withQueryString(),
            'pembelian'     => Pembelian::where('status', 'Belum Bayar')->with('supplier')->latest('id')->get(),
            'kode'          => $this->codeGenerator->pembayaranPembelian(),
            'metode'        => MetodePembayaran::cases(),
        ];
    }

    public function store(array $validated)
    {
        $pembelian = Pembelian::findOrFail($validated['pembelian_id']);
        $validated['kode'] = $this->codeGenerator->pembayaranPembelian();
        $validated['nominal'] = $pembelian->total;
        PembayaranPembelian::create($validated);
    }

    public function update(array $validated, PembayaranPembelian $pembayaranPembelian)
    {
        $validated['nominal'] = Pembelian::findOrFail($validated['pembelian_id'])->total;
        $pembayaranPembelian->update($validated);
    }
}
