<?php

namespace App\Http\Requests\Pembelian;

use Illuminate\Foundation\Http\FormRequest;

abstract class PembelianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['items' => $this->normalizedItems()]);
    }

    protected function normalizedItems(): array
    {
        return collect($this->input('items', []))
            ->filter(fn ($row) => is_array($row)
                && collect($row)->except('id')->contains(fn ($v) => filled($v)))
            ->map(fn (array $row) => $this->normalizeRow($row))
            ->values()
            ->all();
    }

    protected function normalizeRow(array $row): array
    {
        foreach (['harga_beli', 'harga_jual'] as $field) {
            $row[$field] = isset($row[$field])
                ? preg_replace('/\D/', '', (string) $row[$field])
                : null;
        }

        return $row;
    }

    protected function baseRules(): array
    {
        return [
            'tanggal'            => 'required|date',
            'supplier_id'        => 'required|exists:supplier,id',
            'items.*.nama'       => 'required|string|max:255',
            'items.*.kategori'   => 'required|string|max:255',
            'items.*.lingkar'    => 'required|numeric|min:0',
            'items.*.panjang'    => 'required|numeric|min:0',
            'items.*.harga_beli' => 'required|numeric|min:1',
            'items.*.harga_jual' => 'required|numeric|min:1',
        ];
    }
}
