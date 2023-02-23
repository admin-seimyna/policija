<?php

namespace App\Http\Response\Auth;

use App\Http\Response\Response;
use Illuminate\Support\Facades\Auth;

class AuthResponse extends Response
{
    /**
     * @return array
     */
    public function get(): array
    {
        $user = Auth::user();
        if (!$user) {
            return [];
        }

        return [
            'auth/auth' => 1,
            'auth/user' => Auth::user()->load(['userGroup.permissions'])
        ];
    }
}
