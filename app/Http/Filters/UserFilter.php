<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class UserFilter extends Filter
{
    /**
     * @var string[]
     */
    protected array $filters = [
        'search' => 'required',
    ];

    /**
     * @var array|string[]
     */
    protected array $methods = [
        'user_group_id' => 'in',
    ];

    /**
     * @var array|string[]
     */
    protected array $sortable = [
        'name',
        'email',
        'user_group',
        'created_at',
    ];

    /**
     * @param Builder $builder
     * @param string $search
     * @return Builder
     */
    public function filterSearch(Builder $builder, string $search): Builder
    {
        return $builder->where(static function (Builder $builder) use ($search) {
            $builder->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        });
    }

    /**
     * @param Builder $builder
     * @param $value
     * @return Builder
     */
    public function sortUserGroup(Builder $builder, $value): Builder
    {
        return $builder->join('user_groups', function ($join) {
            $join->on('user_groups.id', 'users.user_group_id');
        })->orderBy('user_groups.title', $value);
    }
}
