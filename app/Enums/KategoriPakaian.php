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
            self::ATASAN    => 'Atasan',
            self::BAWAHAN   => 'Bawahan',
            self::TERUSAN   => 'Terusan',
            self::LUARAN    => 'Luaran',
        };
    }
}
