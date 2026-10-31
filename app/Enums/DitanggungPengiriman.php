<?php

namespace App\Enums;

enum DitanggungPengiriman: string
{
    case OWNER = 'owner';
    case PELANGGAN = 'pelanggan';

    public function label(): string
    {
        return match($this) {
            self::OWNER     => 'Owner',
            self::PELANGGAN => 'Pelanggan',
        };
    }
}
