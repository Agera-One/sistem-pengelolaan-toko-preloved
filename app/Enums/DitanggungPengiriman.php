<?php

namespace App\Enums;

enum DitanggungPengiriman: string
{
    case OWNER = 'owner';
    case PELANGGAN = 'pelanggan';

    public function label(): string
    {
        return match($this) {
            self::OWNER => 'Owner',
            self::PELANGGAN => 'Pelanggan',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::OWNER => 'Ongkir dibayar toko',
            self::PELANGGAN => 'Ongkir dibayar pelanggan',
        };
    }
}
