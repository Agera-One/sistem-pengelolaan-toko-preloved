<?php

namespace App\Enums;

enum KurirPengiriman: string
{
    case JNE = 'jne express';
    case JNT = 'j&t express';
    case POSIND = 'pos indonesia';
    case SICEPAT = 'sicepat express';
    case TIKI = 'tiki';
    case ANTERAJA = 'anteraja';
    case SPX = 'spx express';

    public function label(): string
    {
        return match($this) {
            self::JNE       => 'JNE Express',
            self::JNT       => 'J&T Express',
            self::POSIND    => 'Pos Indonesia',
            self::SICEPAT   => 'SiCepat Express',
            self::TIKI      => 'Tiki',
            self::ANTERAJA  => 'Anteraja',
            self::SPX       => 'SPX Express',
        };
    }

    public function logoUrl(): string
    {
        return asset("images/kurir/{$this->value}.png");
    }
}
