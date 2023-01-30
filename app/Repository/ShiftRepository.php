<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Builder;

class ShiftRepository extends Repository
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    public function pagination(Builder $builder): Builder
    {
        return $builder->latest();
    }
}
