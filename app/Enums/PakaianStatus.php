<?php

namespace App\Enums;

enum PakaianStatus: string
{
    case TERSEDIA = 'tersedia';
    case TERJUAL = 'terjual';

    public function label(): string
    {
        return match($this) {
            self::TERSEDIA  => 'Tersedia',
            self::TERJUAL   => 'Terjual',
        };
    }

    public function badgeColor(): string
    {
        return match($this) {
            self::TERSEDIA  => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-300',
            self::TERJUAL   => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-300',
        };
    }

    public function dotColor(): string
    {
        return match($this) {
            self::TERSEDIA  => 'bg-emerald-500',
            self::TERJUAL   => 'bg-blue-500',
        };
    }
}
