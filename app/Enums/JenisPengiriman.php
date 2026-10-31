<?php

namespace App\Enums;

enum JenisPengiriman: string
{
    case KIRIM = 'kirim';
    case RETUR = 'retur';

    public function label(): string
    {
        return match($this) {
            self::KIRIM => 'Kirim',
            self::RETUR => 'Retur',
        };
    }
}
