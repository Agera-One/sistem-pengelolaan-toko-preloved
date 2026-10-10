<?php

namespace App\Http\Requests\Barang;

use Illuminate\Foundation\Http\FormRequest;

class BarangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama'          => 'required|string|max:255',
            'lingkar'       => 'required|numeric|min:1',
            'panjang'       => 'required|numeric|min:1',
            'kategori'      => 'required|string|max:255',
            'harga_jual'    => 'required|numeric|min:1|gt:harga_beli',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'         => 'Nama barang wajib diisi.',
            'nama.max'              => 'Nama barang maksimal 255 karakter.',
            'lingkar.required'      => 'Lingkar wajib diisi.',
            'lingkar.numeric'       => 'Lingkar harus berbentuk angka.',
            'panjang.required'      => 'Panjang wajib diisi.',
            'panjang.numeric'       => 'Panjang harus berbentuk angka.',
            'kategori.required'     => 'Kategori wajib diisi.',
            'kategori.max'          => 'Kategori barang maksimal 255 karakter.',
            'harga_jual.required'   => 'Harga jual wajib diisi.',
            'harga_jual.numeric'    => 'Harga jual harus berbentuk angka.',
        ];
    }
}
