<?php

namespace App\Models;

use App\Traits\HasDateRange;
use App\Traits\Searchable;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranPembelian extends Model
{
    use Searchable, Filterable, HasDateRange;

    protected array $searchable = ['kode'];
    protected array $searchableRelations = ['pembelian' => 'kode'];
    protected array $filterable = ['metode' => 'metode_pembayaran'];

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
