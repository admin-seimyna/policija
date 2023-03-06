<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Builder;

class ShiftRepository extends Repository
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    public static function pagination(Builder $builder): Builder
    {
        return $builder->latest();
    }
}
