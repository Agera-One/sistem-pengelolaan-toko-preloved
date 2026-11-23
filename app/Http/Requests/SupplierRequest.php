<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $supplier = $this->route('supplier');

        return [
            'nama'              => 'required|string|max:255',
            'kabupaten_kota'    => 'required|string|max:255',
            'nomor_telepon'     => [
                'required',
                'max:15',
                Rule::unique('supplier', 'nomor_telepon')->ignore($supplier),
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'             => 'Nama lengkap wajib diisi.',
            'nama.max'                  => 'Nama lengkap maksimal 255 karakter.',
            'kabupaten_kota.required'   => 'kabupaten atau kota wajib diisi.',
            'kabupaten_kota.max'        => 'kabupaten atau kota maksimal 255 karakter.',
            'nomor_telepon.required'    => 'Nomor telepon wajib diisi.',
            'nomor_telepon.max'         => 'Nomor telepon maksimal 15 karakter.',
            'nomor_telepon.unique'      => 'Nomor telepon tidak boleh sama.',
        ];
    }
}
