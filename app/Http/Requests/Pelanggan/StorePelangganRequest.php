<?php

namespace App\Http\Requests\Pelanggan;

use Illuminate\Foundation\Http\FormRequest;

class StorePelangganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama'          => 'required|string|max:255',
            'nomor_telepon' => 'required|max:15|unique:pelanggan,nomor_telepon',
            'alamat'        => 'required',
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
            'alamat.required'        => 'Alamat lengkap wajib diisi.',
        ];
    }
}
