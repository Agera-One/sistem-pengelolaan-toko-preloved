<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PenjualanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'ongkir' => $this->input('ongkir') === '' || $this->input('ongkir') === null
                ? 0
                : $this->input('ongkir'),
        ]);
    }

    public function rules(): array
    {
        return [
            'tanggal'               => 'required|date',
            'pelanggan_id'          => 'required|exists:pelanggan,id',
            'ongkir'                => 'required|integer|min:0',
            'items'                 => 'required|array|min:1',
            'items.*.pakaian_id'    => 'required|distinct|exists:pakaian,id',
            'items.*.harga_jual'    => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal.required'              => 'Tanggal penjualan wajib diisi.',
            'pelanggan_id.required'         => 'Pelanggan wajib dipilih.',
            'items.required'                => 'Tambahkan minimal satu pakaian.',
            'items.min'                     => 'Tambahkan minimal satu pakaian.',
            'items.*.pakaian_id.required'   => 'Pilih pakaian pada setiap baris.',
            'items.*.pakaian_id.distinct'   => 'Ada pakaian yang dipilih lebih dari satu kali.',
            'items.*.harga_jual.required'   => 'Harga jual wajib diisi.',
            'items.*.harga_jual.min'        => 'Harga jual harus lebih dari 0.',
        ];
    }
}
