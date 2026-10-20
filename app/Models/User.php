<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nomor_telepon',
        'alamat'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function pembelian(): HasMany
    {
        return $this->hasMany(Pembelian::class, 'user_id');
    }

    public function penjualan(): HasMany
    {
        return $this->hasMany(Penjualan::class, 'user_id');
    }

    protected function inisial(): Attribute
    {
        return Attribute::make(
            get: function () {
                $nama = trim($this->name ?? '?');

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
