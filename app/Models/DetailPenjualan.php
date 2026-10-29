<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPenjualan extends Model
{
    protected $table = 'detail_penjualan';

    public $timestamps = false;

    protected $fillable = [
        'pakaian_id',
        'penjualan_id',
        'pengiriman_id',
        'harga_jual',
    ];

    public function pakaian(): BelongsTo
    {
        return $this->belongsTo(Pakaian::class, 'pakaian_id');
    }

    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }
}
