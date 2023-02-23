<?php

namespace App\Policies;

use App\Enum\RoleEnum;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $auth
     * @param User $user
     * @param string $permission
     * @return bool
     */
    public function permission(User $auth, User $user, string $permission): bool
    {
        [$group, $action] = explode('.', $permission);
        return $user->role === RoleEnum::OWNER || $user->userGroup()->whereHas('permissions', function ($query) use ($group, $action) {
            return $query->where('group', $group)->where('action', $action)->where('value', 1);
        })->exists();
    }
}
