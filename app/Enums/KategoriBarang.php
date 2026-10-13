<?php

namespace App\Enums;

enum KategoriBarang: string
{
    case BLOUSE = 'blouse';
    case KEMEJA = 'kemeja';
    case ROK = 'rok';
    case CELANA = 'celana';
    case OVERALL = 'overall';
    case OUTER = 'outher';
    case JAKET = 'jaket';
    case VEST = 'vest';
    case GAMIS = 'gamis';
    case DRESS = 'dress';

    public function label(): string
    {
        return match($this) {
            self::BLOUSE   => 'Blouse',
            self::KEMEJA   => 'Kemeja',
            self::ROK      => 'Rok',
            self::CELANA   => 'Celana',
            self::OVERALL  => 'Overall',
            self::OUTER    => 'Outher',
            self::JAKET    => 'Jaket',
            self::VEST     => 'Vest',
            self::GAMIS    => 'Gamis',
            self::DRESS    => 'Dress',
        };
    }
}
