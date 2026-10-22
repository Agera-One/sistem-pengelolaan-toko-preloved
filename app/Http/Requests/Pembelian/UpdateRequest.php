<?php

namespace App\Http\Requests\Pembelian;

use Illuminate\Support\Collection;

class UpdateRequest extends PembelianRequest
{
    protected ?Collection $lockedPakaian = null;

    public function rules(): array
    {
        return $this->baseRules() + [
            'items'      => $this->lockedPakaian()->isNotEmpty()
                ? 'nullable|array'
                : 'required|array|min:1',
            'items.*.id' => 'nullable|integer',
        ];
    }

    protected function normalizeRow(array $row): array
    {
        $row = parent::normalizeRow($row);

        if ($locked = $this->lockedPakaian()->get((int) ($row['id'] ?? 0))) {
            $row['harga_beli'] = (string) (int) $locked->harga_beli;
        }

        return $row;
    }

    protected function lockedPakaian(): Collection
    {
        return $this->lockedPakaian ??= $this->route('pembelian')
            ->pakaian()
            ->where('status', '!=', 'Tersedia')
            ->get()
            ->keyBy('id');
    }
}
