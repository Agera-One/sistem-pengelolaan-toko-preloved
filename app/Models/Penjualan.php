<?php

namespace App\Models;

use App\Enums\PenjualanStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Filterable;
use App\Traits\Searchable;
use App\Traits\HasDateRange;

class Penjualan extends Model
{
    use Searchable, Filterable, HasDateRange;

    protected array $searchable = ['kode'];
    protected array $searchableRelations = ['pelanggan' => 'nama'];
    protected array $filterable = ['status'];

    protected $table = 'penjualan';

    protected $fillable = [
        'tanggal',
        'kode',
        'ongkir',
        'subtotal',
        'total',
        'status',
        'user_id',
        'pelanggan_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal'   => 'date',
            'status'    => PenjualanStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function detailPenjualan(): HasMany
    {
        return $this->hasMany(DetailPenjualan::class, 'penjualan_id');
    }

    public function detailRetur(): HasMany
    {
        return $this->hasMany(DetailRetur::class, 'penjualan_id');
    }

    public function pakaian(): BelongsToMany
    {
        return $this->belongsToMany(Pakaian::class, 'detail_penjualan', 'penjualan_id', 'pakaian_id')
            ->withPivot('harga_jual');
    }

    public function retur(): BelongsToMany
    {
        return $this->belongsToMany(Retur::class, 'detail_retur', 'penjualan_id', 'retur_id');
    }
}
