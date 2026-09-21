<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    protected $table = 'barang';

    protected $fillable = [
        'kode',
        'nama',
        'lingkar',
        'panjang',
        'harga_jual',
        'kategori',
        'status',
    ];

    public function detailPembelian(): HasMany
    {
        return $this->hasMany(DetailPembelian::class, 'barang_id');
    }

    public function detailPenjualan(): HasMany
    {
        return $this->hasMany(DetailPenjualan::class, 'barang_id');
    }

    public function pembelian(): BelongsToMany
    {
        return $this->belongsToMany(Pembelian::class, 'detail_pembelian', 'barang_id', 'pembelian_id')
            ->withPivot('harga_beli');
    }

    public function penjualan(): BelongsToMany
    {
        return $this->belongsToMany(Penjualan::class, 'detail_penjualan', 'barang_id', 'penjualan_id')
            ->withPivot('harga_jual');
    }
}
