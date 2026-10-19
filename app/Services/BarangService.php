<?php

namespace App\Services;

use App\Enums\BarangStatus;
use App\Enums\KategoriBarang;
use App\Models\Barang;

class BarangService
{
    public function __construct(
        protected CodeGeneratorService $codeGenerator,
    ) {}

    public function index(array $query): array
    {
        return [
            'barang'    => Barang::search($query['q'] ?? null)
                ->filter($query)
                ->latest('id')
                ->paginate(10)
                ->withQueryString(),
            'statuses'  => BarangStatus::cases(),
            'kategori'  => KategoriBarang::cases(),
        ];
    }
}
