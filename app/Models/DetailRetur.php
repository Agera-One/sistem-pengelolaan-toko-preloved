<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailRetur extends Model
{
    protected $table = 'detail_retur';

    public $timestamps = false;

    protected $fillable = [
        'penjualan_id',
        'retur_id',
    ];

    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    public function retur(): BelongsTo
    {
        return $this->belongsTo(Retur::class, 'retur_id');
    }
}
