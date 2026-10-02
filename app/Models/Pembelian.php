<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pembelian extends Model
{
    protected $table = 'pembelian';

    protected $fillable = [
        'kode',
        'tanggal',
        'total',
        'status',
        'supplier_id',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(PembayaranPembelian::class, 'pembelian_id');
    }

    public function barang(): HasMany
    {
        return $this->hasMany(Barang::class, 'pembelian_id');
    }


    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
