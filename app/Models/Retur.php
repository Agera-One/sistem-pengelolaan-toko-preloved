<?php

namespace App\Models;

use App\Enums\MetodePembayaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Retur extends Model
{
    protected $table = 'retur';

    protected $fillable = [
        'penjualan_id',
        'user_id',
        'kode',
        'tanggal',
        'total_refund',
        'metode_refund',
    ];

    protected function casts(): array
    {
        return [
            'tanggal'       => 'date',
            'metode_refund' => MetodePembayaran::class
        ];
    }

    public function detailRetur(): HasMany
    {
        return $this->hasMany(DetailRetur::class, 'retur_id');
    }

    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
