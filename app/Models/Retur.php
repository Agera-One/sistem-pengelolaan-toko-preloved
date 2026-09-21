<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Retur extends Model
{
    protected $table = 'retur';

    protected $fillable = [
        'tanggal',
        'kode',
        'kerugian_pengiriman',
        'alasan',
        'tipe',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function detailRetur(): HasMany
    {
        return $this->hasMany(DetailRetur::class, 'retur_id');
    }

    public function penjualan(): BelongsToMany
    {
        return $this->belongsToMany(Penjualan::class, 'detail_retur', 'retur_id', 'penjualan_id');
    }
}
