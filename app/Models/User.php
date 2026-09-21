<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

}
