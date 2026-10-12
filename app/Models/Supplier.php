<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use Searchable;

    protected array $searchable = ['kode', 'nama', 'nomor_telepon', 'kota'];

    protected $table = 'supplier';

    protected $fillable = [
        'kode',
        'nama',
        'nomor_telepon',
        'kota',
    ];

    public function pembelian(): HasMany
    {
        return $this->hasMany(Pembelian::class, 'supplier_id');
    }
}
