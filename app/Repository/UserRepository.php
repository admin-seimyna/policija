<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Builder;

class UserRepository extends Repository
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    public function pagination(Builder $builder): Builder
    {
        return $builder->with(['userGroup:id,title'])->latest();
    }
}
