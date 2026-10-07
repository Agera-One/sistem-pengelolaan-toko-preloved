<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranPembelian extends Model
{
    protected $table = 'pembayaran_pembelian';

    protected $fillable = [
        'kode',
        'tanggal',
        'nominal',
        'metode_pembayaran',
        'pembelian_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function pembelian(): BelongsTo
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id');
    }
}
