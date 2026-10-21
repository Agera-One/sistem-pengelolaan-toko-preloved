<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PembayaranPenjualanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'penjualan_id'          => 'required|exists:penjualan,id',
            'tanggal'               => 'required|date',
            'metode_pembayaran'     => 'required|in:tunai,transfer',
        ];
    }

    public function messages(): array
    {
        return [
            'penjualan_id.required'      => 'Penjualan wajib dipilih',
            'penjualan_id.exists'        => 'Data penjualan tidak ditemukan.',
            'tanggal.required'           => 'Tanggal bayar wajib diisi.',
            'tanggal.date'               => 'Format tanggal tidak valid.',
            'metode_pembayaran.required' => 'Pilih metode pembayaran.',
            'metode_pembayaran.in'       => 'Metode pembayaran harus tunai atau transfer.',
        ];
    }
}
