<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Supplier extends Model
{
    use Searchable;

    protected array $searchable = ['kode', 'nama', 'nomor_telepon', 'kabupaten_kota'];

    protected $table = 'supplier';

    protected $fillable = [
        'kode',
        'nama',
        'nomor_telepon',
        'kabupaten_kota',
    ];

    public function pembelian(): HasMany
    {
        return $this->hasMany(Pembelian::class, 'supplier_id');
    }

    protected function inisial(): Attribute
    {
        return Attribute::make(
            get: function () {
                $nama = trim($this->nama ?? '?');

                return str_word_count($nama) === 1
                    ? Str::upper(Str::substr($nama, 0, 2))
                    : Str::of($nama)
                        ->explode(' ')
                        ->filter()
                        ->take(2)
                        ->map(fn ($kata) => Str::upper(Str::substr($kata, 0, 1)))
                        ->implode('');
            }
        );
    }
}
