<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Barang extends Model
{
    protected $table = 'barang';

    protected $fillable = [
        'kode',
        'nama',
        'lingkar',
        'panjang',
        'kategori',
        'status',
    ];

    public function detailPenjualan(): HasMany
    {
        return $this->hasMany(DetailPenjualan::class, 'barang_id');
    }

    public function pembelian(): BelongsTo
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id');
    }

    public function penjualan(): BelongsToMany
    {
        return $this->belongsToMany(Penjualan::class, 'detail_penjualan', 'barang_id', 'penjualan_id')
            ->withPivot('harga_jual');
    }
}
