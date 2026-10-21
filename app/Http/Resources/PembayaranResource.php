<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class PembayaranResource extends JsonResource
{
    abstract protected function relasi(): string;

    abstract protected function routePrefix(): string;

    abstract protected function namaPihak(Model $transaksi): ?string;

    abstract protected function keyPihak(): string;

    public function toArray(Request $request): array
    {
        $relasi    = $this->relasi();
        $transaksi = $this->resource->{$relasi};

        return [
            'id'                => $this->getKey(),
            'kode_bayar'        => $this->kode,
            'tanggal'           => $this->tanggal->toDateString(),
            'nominal'           => $this->nominal,
            'metode_pembayaran' => $this->metode_pembayaran->value,
            'metode_label'      => $this->metode_pembayaran->label(),
            "{$relasi}_id"      => $this->resource->{"{$relasi}_id"},
            $relasi             => $transaksi ? $this->transaksi($transaksi) : null,
            'url_update'        => route("{$this->routePrefix()}.update", $this->resource),
        ];
    }

    private function transaksi(Model $transaksi): array
    {
        return [
            'id'                => $transaksi->getKey(),
            'kode'              => $transaksi->kode,
            'tanggal'           => $transaksi->tanggal->toDateString(),
            'total'             => $transaksi->total,
            $this->keyPihak()   => $this->namaPihak($transaksi),
        ];
    }
}
