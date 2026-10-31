<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class PengirimanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pengiriman = $this->route('pengiriman');

        return [
            'penjualan_id'      => 'required|exists:penjualan,id',
            'nomor_resi'     => [
                'required',
                'max:20',
                Rule::unique('pengiriman', 'nomor_resi')->ignore($pengiriman),
            ],
            'jenis'             => 'required|in:kirim,retur',
            'kurir'             => 'required|in:jne express, j&t express, pos indonesia, sicepat express, tiki, anteraja, spx express',
            'ongkir'            => 'required|integer|min:0',
            'ditanggung_oleh'   => 'required|in:owner,pelanggan',
            'tanggal'           => 'required|date',
            'detail_ids'        => 'required|array|min:1',
            'detail_ids.*'      => 'integer|distinct',
        ];
    }

    public function messages(): array
    {
        return [
            'penjualan_id.required'     => 'Penjualan wajib dipilih',
            'penjualan_id.exists'       => 'Data penjualan tidak ditemukan.',
            'nomor_resi.required'       => 'Nomor resi wajib diisi.',
            'nomor_resi.max'            => 'Nomor resi maksimal 20 karakter.',
            'nomor_resi.unique'         => 'Nomor resi tidak boleh sama.',
            'jenis.required'            => 'Jenis pengiriman wajib dipilih.',
            'jenis.in'                  => 'Jenis pengiriman harus kirim atau retur.',
            'kurir.required'            => 'Kurir wajib dipilih.',
            'kurir.in'                  => 'Kurir yang dipilih tidak valid',
            'ditanggung_oleh.required'  => 'Ditanggung oleh wajib dipilih.',
            'ditanggung_oleh.in'        => 'Ditanggung oleh harus owner atau pelanggan.',
            'tanggal.required'          => 'Tanggal bayar wajib diisi.',
            'tanggal.date'              => 'Format tanggal tidak valid.',
        ];
    }
}
