<?php

namespace App\Enums;

enum KategoriPakaian: string
{
    case ATASAN = 'atasan';
    case BAWAHAN = 'bawahan';
    case TERUSAN = 'terusan';
    case LUARAN = 'luaran';

    public function label(): string
    {
        return match($this) {
            self::ATASAN   => 'Blouse',
            self::BAWAHAN   => 'Kemeja',
            self::TERUSAN      => 'Rok',
            self::LUARAN   => 'Celana',
        };
    }
}
