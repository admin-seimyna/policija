<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Builder;

class UserRepository extends Repository
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    public static function pagination(Builder $builder): Builder
    {
        return $builder->select([
            'users.id',
            'users.name',
            'users.email',
            'users.user_group_id',
            'users.created_at'
        ])->with(['userGroup:id,title'])->latest();
    }
}
