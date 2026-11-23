<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasPayload
{
    abstract protected function resourceClass(): string;

    abstract protected function payloadRelations(): array;

    protected function payload(): Attribute
    {
        return Attribute::get(function () {
            $resource = $this->resourceClass();

            return (new $resource($this))->resolve();
        });
    }

    public function scopeDenganRelasi(Builder $query): Builder
    {
        return $query->with($this->payloadRelations());
    }
}
