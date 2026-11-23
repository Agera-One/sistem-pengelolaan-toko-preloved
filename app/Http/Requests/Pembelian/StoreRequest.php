<?php

namespace App\Http\Requests\Pembelian;

class StoreRequest extends PembelianRequest
{
    public function rules(): array
    {
        return $this->baseRules() + [
            'items' => 'required|array|min:1',
        ];
    }
}
