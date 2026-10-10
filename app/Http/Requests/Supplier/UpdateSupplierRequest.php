<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama'          => 'required|string|max:255',
            'nomor_telepon' => 'required|max:15|unique:supplier,nomor_telepon,' . $this->route('supplier')->id . ',id',
            'kota'          => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'          => 'Nama lengkap wajib diisi.',
            'nama.max'               => 'Nama lengkap maksimal 255 karakter.',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',
            'nomor_telepon.max'      => 'Nomor telepon maksimal 15 karakter.',
            'nomor_telepon.unique'   => 'Nomor telepon tidak boleh sama.',
            'kota.required'          => 'kota wajib diisi.',
            'kota.max'               => 'kota maksimal 255 karakter.',
        ];
    }
}
