<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait HasDateRange
{
    public function scopeDateBetween(Builder $query, ?string $mulai, ?string $selesai): Builder
    {
        $column = $this->dateColumn ?? 'tanggal';
        $valid  = fn ($v) => is_string($v) && Carbon::hasFormat($v, 'Y-m-d');

        return $query
            ->when($valid($mulai), fn ($q) => $q->where($column, '>=', $mulai . ' 00:00:00'))
            ->when($valid($selesai), fn ($q) => $q->where($column, '<=', $selesai . ' 23:59:59'));
    }
}
