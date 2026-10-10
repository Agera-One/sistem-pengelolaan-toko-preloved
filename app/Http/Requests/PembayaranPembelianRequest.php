<?php

namespace App\Http\Requests\PembayaranPembelian;

use Illuminate\Foundation\Http\FormRequest;

class PembayaranPembelianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pembelian_id'          => 'required|exists:pembelian,id',
            'tanggal'               => 'required|date',
            'metode_pembayaran'     => 'required|in:Tunai,Transfer',
        ];
    }

    public function messages(): array
    {
        return [
            'pembelian_id.required'      => 'Pembelian wajib dipilih',
            'pembelian_id.exists'        => 'Data pembelian tidak ditemukan.',
            'tanggal.required'           => 'Tanggal bayar wajib diisi.',
            'tanggal.date'               => 'Format tanggal tidak valid.',
            'metode_pembayaran.required' => 'Pilih metode pembayaran.',
            'metode_pembayaran.in'       => 'Metode pembayaran harus Tunai atau Transfer.',
        ];
    }
}
