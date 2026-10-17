<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Searchable
{
    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        $keyword = trim((string) $keyword);

        if ($keyword === '') {
            return $query;
        }

        $like = '%' . addcslashes($keyword, '%_\\') . '%';

        return $query->where(function ($q) use ($like) {
            foreach ($this->searchable ?? [] as $column) {
                $q->orWhere($column, 'like', $like);
            }

            foreach ($this->searchableRelations ?? [] as $relation => $column) {
                $q->orWhereHas($relation, fn ($s) => $s->where($column, 'like', $like));
            }
        });
    }
}
