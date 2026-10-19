<?php

namespace App\Services;

use App\Models\Pelanggan;

class PelangganService
{
    public function __construct(
        protected CodeGeneratorService $codeGenerator,
    ) {}

    public function index(array $query): array
    {
        return [
            'pelanggan'    => Pelanggan::search($query['q'] ?? null)
                ->latest('id')
                ->paginate(10)
                ->withQueryString(),
            'kode'  => $this->codeGenerator->pelanggan(),
        ];
    }

    public function store(array $validated)
    {
        $validated['kode'] = $this->codeGenerator->pelanggan();
        Pelanggan::create($validated);
    }
}
