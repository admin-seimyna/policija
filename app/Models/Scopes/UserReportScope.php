<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserReportScope implements Scope
{
    /**
     * @param Builder $builder
     * @param Model $model
     */
    public function apply(Builder $builder, Model $model)
    {
        $user = Auth::user();
        $builder->whereHas('user');
        if (!Gate::check('permission', [$user, 'report.list'])) {
            $builder->where('user_id', $user->id);
        }
    }
}
