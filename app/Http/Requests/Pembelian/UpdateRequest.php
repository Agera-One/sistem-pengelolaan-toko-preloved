<?php

namespace App\Http\Requests\Pembelian;

use Illuminate\Support\Collection;

class UpdateRequest extends PembelianRequest
{
    protected ?Collection $lockedBarang = null;

    public function rules(): array
    {
        return $this->baseRules() + [
            'items'      => $this->lockedBarang()->isNotEmpty()
                ? 'nullable|array'
                : 'required|array|min:1',
            'items.*.id' => 'nullable|integer',
        ];
    }

    protected function normalizeRow(array $row): array
    {
        $row = parent::normalizeRow($row);

        if ($locked = $this->lockedBarang()->get((int) ($row['id'] ?? 0))) {
            $row['harga_beli'] = (string) (int) $locked->harga_beli;
        }

        return $row;
    }

    protected function lockedBarang(): Collection
    {
        return $this->lockedBarang ??= $this->route('pembelian')
            ->barang()
            ->where('status', '!=', 'Tersedia')
            ->get()
            ->keyBy('id');
    }
}
