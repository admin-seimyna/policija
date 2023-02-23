<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UpdateRequest extends CreateRequest
{
    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        $user = Auth::user();
        if (!Gate::check('permission', [Auth::user(), 'report.edit'])) {
            return $user->id;
        }
        return (int)$this->input('user_id');
    }
}
