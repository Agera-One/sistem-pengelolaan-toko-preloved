<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PembayaranPembelianResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $pembelian = $this->pembelian;

        return [
            'id'                => $this->getKey(),
            'kode_bayar'        => $this->kode,
            'tanggal'           => $this->tanggal->toDateString(),
            'nominal'           => $this->nominal,
            'metode_pembayaran' => $this->metode_pembayaran->value,
            'pembelian_id'      => $this->pembelian_id,
            'pembelian'         => [
                'id'       => $pembelian->id,
                'kode'     => $pembelian->kode,
                'tanggal'  => $pembelian->tanggal->toDateString(),
                'total'    => $pembelian->total,
                'supplier' => $pembelian->supplier->nama,
            ],
            'url_update'        => route('pembayaran-pembelian.update', $this->resource),
        ];
    }
}
