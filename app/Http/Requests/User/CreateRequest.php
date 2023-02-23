<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->whereNull('deleted_at')
            ],
            'user_group_id' => 'required|exists:user_groups,id',
            'password' => 'required',
        ];

        if (Auth::user()->isOwner) {
            $rules['user_group_id'] = 'required_if:role,null|exists:user_groups,id';
        }

        return $rules;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $data = [
            'name' => $this->input('name'),
            'email' => mb_strtolower($this->input('email')),
            'user_group_id' => $this->input('user_group_id'),
            'password' => $this->input('password', uniqid()),
        ];

        if (Auth::user()->isOwner) {
            $data['role'] = $this->input('role', null);
        }
        return $data;
    }
}
