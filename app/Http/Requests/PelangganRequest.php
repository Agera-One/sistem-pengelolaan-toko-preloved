<?php

namespace App\Http\Requests;

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
            'nama'              => 'required|string|max:255',
            'provinsi'          => 'required|string|max:255',
            'kabupaten_kota'    => 'required|string|max:255',
            'kecamatan'         => 'required|string|max:255',
            'kelurahan'         => 'required|string|max:255',
            'alamat'            => 'required',
            'kode_pos'          => 'required|string|max:5',
            'nomor_telepon'     => [
                'required',
                'max:15',
                Rule::unique('pelanggan', 'nomor_telepon')->ignore($pelanggan),
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'             => 'Nama lengkap wajib diisi.',
            'nama.max'                  => 'Nama lengkap maksimal 255 karakter.',
            'provinsi.required'         => 'Provinsi wajib diisi.',
            'provinsi.max'              => 'Provinsi maksimal 255 karakter.',
            'kabupaten_kota.required'   => 'Kota atau kabupaten wajib diisi.',
            'kabupaten_kota.max'        => 'Kota atau kabupaten maksimal 255 karakter.',
            'kecamatan.required'        => 'Kecamatan wajib diisi.',
            'kecamatan.max'             => 'Kecamatan maksimal 255 karakter.',
            'kelurahan.required'        => 'Kelurahan wajib diisi.',
            'kelurahan.max'             => 'Kelurahan maksimal 255 karakter.',
            'kode_pos.required'         => 'Kode pos wajib diisi.',
            'kode_pos.max'              => 'Kode pos maksimal 5 karakter.',
            'alamat.required'           => 'Alamat lengkap wajib diisi.',
            'nomor_telepon.required'    => 'Nomor telepon wajib diisi.',
            'nomor_telepon.max'         => 'Nomor telepon maksimal 15 karakter.',
            'nomor_telepon.unique'      => 'Nomor telepon tidak boleh sama.',
        ];
    }
}
