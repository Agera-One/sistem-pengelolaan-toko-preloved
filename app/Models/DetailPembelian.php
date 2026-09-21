<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPembelian extends Model
{
    protected $table = 'detail_pembelian';

    public $timestamps = false;

    protected $fillable = [
        'barang_id',
        'pembelian_id',
        'harga_beli',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function pembelian(): BelongsTo
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id');
    }
}
