<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        foreach ($this->filterable ?? [] as $param => $column) {
            $param = is_int($param) ? $column : $param;
            $value = $filters[$param] ?? null;

            if (is_array($value)) {
                $value = array_values(array_filter($value, fn ($v) => is_scalar($v) && filled($v)));

                if ($value !== []) {
                    $query->whereIn($column, $value);
                }
            } elseif (is_scalar($value) && filled($value)) {
                $query->where($column, $value);
            }
        }

        return $query;
    }
}
