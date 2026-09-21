<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penjualan extends Model
{
    protected $table = 'penjualan';

    protected $fillable = [
        'tanggal',
        'kode',
        'ongkir',
        'total',
        'user_id',
        'pelanggan_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function detailPenjualan(): HasMany
    {
        return $this->hasMany(DetailPenjualan::class, 'penjualan_id');
    }

    public function detailRetur(): HasMany
    {
        return $this->hasMany(DetailRetur::class, 'penjualan_id');
    }

    public function barang(): BelongsToMany
    {
        return $this->belongsToMany(Barang::class, 'detail_penjualan', 'penjualan_id', 'barang_id')
            ->withPivot('harga_jual');
    }

    public function retur(): BelongsToMany
    {
        return $this->belongsToMany(Retur::class, 'detail_retur', 'penjualan_id', 'retur_id');
    }
}
