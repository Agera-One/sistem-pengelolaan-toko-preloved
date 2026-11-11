<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailRetur extends Model
{
    protected $table = 'detail_retur';

    public $timestamps = false;

    protected $fillable = [
        'retur_id',
        'detail_penjualan_id',
        'harga',
        'alasan'
    ];

    public function detailPenjualan(): BelongsTo
    {
        return $this->belongsTo(DetailPenjualan::class, 'detail_penjualan_id');
    }

    public function retur(): BelongsTo
    {
        return $this->belongsTo(Retur::class, 'retur_id');
    }
}
