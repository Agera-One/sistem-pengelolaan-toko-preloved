<?php

namespace App\Models;

use App\Enums\PakaianStatus;
use App\Enums\KategoriPakaian;
use App\Traits\Searchable;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pakaian extends Model
{
    use Searchable, Filterable;

    protected array $searchable = ['kode', 'nama'];
    protected array $filterable = ['kategori', 'status'];

    protected $table = 'pakaian';

    protected $fillable = [
        'kode',
        'nama',
        'lingkar',
        'panjang',
        'kategori',
        'harga_beli',
        'harga_jual',
        'status',
        'pembelian_id',
    ];

    protected function casts(): array
    {
        return [
            'status'    => PakaianStatus::class,
            'kategori'  => KategoriPakaian::class,
        ];
    }

    public function detailPenjualan(): HasMany
    {
        return $this->hasMany(DetailPenjualan::class, 'pakaian_id');
    }

    public function pembelian(): BelongsTo
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id');
    }

    public function penjualan(): BelongsToMany
    {
        return $this->belongsToMany(Penjualan::class, 'detail_penjualan', 'pakaian_id', 'penjualan_id')
            ->withPivot('harga_jual');
    }
}
