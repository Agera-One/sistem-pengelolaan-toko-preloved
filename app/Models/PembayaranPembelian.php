<?php

namespace App\Models;

use App\Enums\MetodePembayaran;
use App\Http\Resources\PembayaranPembelianResource;
use App\Traits\Filterable;
use App\Traits\HasDateRange;
use App\Traits\HasPayload;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranPembelian extends Model
{
    use Searchable, Filterable, HasDateRange, HasPayload;

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
            'tanggal'           => 'date',
            'metode_pembayaran' => MetodePembayaran::class,
        ];
    }

    public function pembelian(): BelongsTo
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id');
    }

    protected function resourceClass(): string
    {
        return PembayaranPembelianResource::class;
    }

    protected function payloadRelations(): array
    {
        return ['pembelian.supplier'];
    }
}
