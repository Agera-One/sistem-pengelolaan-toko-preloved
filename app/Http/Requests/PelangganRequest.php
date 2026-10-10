<?php

namespace App\Http\Requests\Pelanggan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PelangganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pelanggan = $this->route('pelanggan');

        return [
            'nama'          => 'required|string|max:255',
            'alamat'        => 'required',
            'nomor_telepon' => [
                'required',
                'max:15',
                Rule::unique('pelanggan', 'nomor_telepon')->ignore($pelanggan),
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'          => 'Nama lengkap wajib diisi.',
            'nama.max'               => 'Nama lengkap maksimal 255 karakter.',
            'alamat.required'        => 'Alamat lengkap wajib diisi.',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',
            'nomor_telepon.max'      => 'Nomor telepon maksimal 15 karakter.',
            'nomor_telepon.unique'   => 'Nomor telepon tidak boleh sama.',
        ];
    }
}
