<?php

namespace App\Services;

use App\Models\Supplier;

class SupplierService
{
    public function __construct(
        protected CodeGeneratorService $codeGenerator,
    ) {}

    public function index(array $query): array
    {
        return [
            'supplier'  => Supplier::search($query['q'] ?? null)
                ->latest('id')
                ->paginate(10)
                ->withQueryString(),
            'kode'      => $this->codeGenerator->supplier(),
        ];
    }

    public function store(array $validated)
    {
        $validated['kode'] = $this->codeGenerator->supplier();
        Supplier::create($validated);
    }
}
