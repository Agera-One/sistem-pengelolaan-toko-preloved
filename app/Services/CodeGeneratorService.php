<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class CodeGeneratorService
{
    public function generate(
        Model $model,
        string $column,
        string $prefix
    ): string {
        $period = now()->format('Ym');

        $lastRecord = $model
            ->where($column, 'like', "{$prefix}-{$period}-%")
            ->orderByDesc($column)
            ->first();

        $number = $lastRecord
            ? ((int) substr($lastRecord->{$column}, -4)) + 1
            : 1;

        return sprintf(
            '%s-%s-%04d',
            $prefix,
            $period,
            $number
        );
    }
}
