<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        foreach ($this->filterable ?? [] as $param => $column) {
            // ['status'] => param & kolom sama, ['metode' => 'metode_pembayaran'] => beda nama
            $param = is_int($param) ? $column : $param;
            $value = $filters[$param] ?? null;

            if (is_scalar($value) && filled($value)) {
                $query->where($column, $value);
            }
        }

        return $query;
    }
}
