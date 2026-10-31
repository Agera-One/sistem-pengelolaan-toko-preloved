<?php

namespace App\Models;

use App\Enums\DitanggungPengiriman;
use App\Enums\JenisPengiriman;
use App\Traits\Filterable;
use App\Traits\Searchable;
use App\Traits\HasDateRange;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengiriman extends Model
{
    use Searchable, Filterable, HasDateRange;

    protected array $searchable = ['nomor_resi'];
    protected array $searchableRelations = ['penjualan' => 'kode'];
    protected array $filterable = ['jenis', 'kurir', 'ditanggung_oleh'];

    protected $table = 'pengiriman';

    protected $fillable = [
        'penjualan_id',
        'nomor_resi',
        'jenis',
        'kurir',
        'ongkir',
        'ditanggung_oleh',
        'tanggal',
    ];

    protected function casts(): array
    {
        return [
            'tanggal'           => 'date',
            'jenis'             => JenisPengiriman::class,
            'ditanggung_oleh'   => DitanggungPengiriman::class,
        ];
    }

    public function detailPenjualan(): HasMany
    {
        return $this->hasMany(DetailPenjualan::class, 'pengiriman_id');
    }


    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }
}
