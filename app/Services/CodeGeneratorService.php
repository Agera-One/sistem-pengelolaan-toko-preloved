<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class CodeGeneratorService
{
    public function generate(Model $model, string $column, string $prefix): string
    {
        return $this->generateBatch($model, $column, $prefix, 1)[0];
    }

    public function generateBatch(Model $model, string $column, string $prefix, int $count): array
    {
        if ($count < 1) {
            return [];
        }

        $period = now()->format('Ym');

        $last = $model->newQuery()
            ->where($column, 'like', "{$prefix}-{$period}-%")
            ->orderByDesc($column)
            ->lockForUpdate()
            ->value($column);

        $start = $last ? ((int) substr($last, -4)) + 1 : 1;

        return array_map(
            fn (int $number) => sprintf('%s-%s-%04d', $prefix, $period, $number),
            range($start, $start + $count - 1)
        );
    }
}
