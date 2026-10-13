<?php

namespace App\Models;

use App\Enums\BarangStatus;
use App\Traits\Searchable;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Barang extends Model
{
    use Searchable, Filterable;

    protected array $searchable = ['kode', 'nama'];
    protected array $filterable = ['kategori', 'status'];

    protected $table = 'barang';

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
            'status' => BarangStatus::class,
        ];
    }

    public function detailPenjualan(): HasMany
    {
        return $this->hasMany(DetailPenjualan::class, 'barang_id');
    }

    public function pembelian(): BelongsTo
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id');
    }

    public function penjualan(): BelongsToMany
    {
        return $this->belongsToMany(Penjualan::class, 'detail_penjualan', 'barang_id', 'penjualan_id')
            ->withPivot('harga_jual');
    }
}
