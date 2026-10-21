<?php

namespace App\Models;

use App\Enums\MetodePembayaran;
use App\Http\Resources\PembayaranPenjualanResource;
use App\Traits\Filterable;
use App\Traits\HasDateRange;
use App\Traits\HasPayload;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranPenjualan extends Model
{
    use Searchable, Filterable, HasDateRange, HasPayload;

    protected array $searchable = ['kode'];
    protected array $searchableRelations = ['penjualan' => 'kode'];
    protected array $filterable = ['metode' => 'metode_pembayaran'];

    protected $table = 'pembayaran_penjualan';

    protected $fillable = [
        'kode',
        'tanggal',
        'nominal',
        'metode_pembayaran',
        'penjualan_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal'           => 'date',
            'metode_pembayaran' => MetodePembayaran::class,
        ];
    }

    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    protected function resourceClass(): string
    {
        return PembayaranPenjualanResource::class;
    }

    protected function payloadRelations(): array
    {
        return ['penjualan.pelanggan'];
    }
}
