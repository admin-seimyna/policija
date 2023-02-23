<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserObserver
{
    /**
     * @param User $user
     */
    public function creating(User $user): void
    {
        $user->password = Hash::make($user->password);
    }

    /**
     * @param User $user
     */
    public function updating(User $user): void
    {
        $dirty = $user->getDirty();
        if (!empty($dirty['password'])) {
            $user->password = Hash::make($dirty['password']);
        } else {
            $user->password = $user->getOriginal('password');
        }
    }
}
