<?php

namespace App\Services;

use App\Enums\PakaianStatus;
use App\Enums\KategoriPakaian;
use App\Models\Pakaian;

class PakaianService
{
    public function __construct(
        protected CodeGeneratorService $codeGenerator,
    ) {}

    public function index(array $query): array
    {
        return [
            'pakaian'    => Pakaian::search($query['q'] ?? null)
                ->filter($query)
                ->latest('id')
                ->paginate(10)
                ->withQueryString(),
            'statuses'  => PakaianStatus::cases(),
            'kategori'  => KategoriPakaian::cases(),
        ];
    }
}
