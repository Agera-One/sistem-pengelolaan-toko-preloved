<?php

namespace App\Enums;

enum PenjualanStatus: string
{
    case BELUM_BAYAR = 'belum bayar';
    case SUDAH_BAYAR = 'sudah bayar';

    public function label(): string
    {
        return match($this) {
            self::BELUM_BAYAR   => 'Belum Bayar',
            self::SUDAH_BAYAR   => 'Sudah Bayar',
        };
    }

    public function badgeColor(): string
    {
        return match($this) {
            self::BELUM_BAYAR   => 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-300',
            self::SUDAH_BAYAR   => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-300',
        };
    }

    public function dotColor(): string
    {
        return match($this) {
            self::BELUM_BAYAR   => 'bg-red-500',
            self::SUDAH_BAYAR   => 'bg-emerald-500',
        };
    }
}
