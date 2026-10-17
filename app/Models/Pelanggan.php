<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelanggan extends Model
{
    use Searchable;

    protected array $searchable = ['kode', 'nama', 'nomor_telepon', 'alamat'];

    protected $table = 'pelanggan';

    protected $fillable = [
        'kode',
        'nama',
        'nomor_telepon',
        'alamat',
    ];

    public function penjualan(): HasMany
    {
        return $this->hasMany(Penjualan::class, 'pelanggan_id');
    }
}
