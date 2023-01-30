<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class CreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'user_group_id' => 'required|exists:user_groups,id'
        ];
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            'name' => $this->input('name'),
            'email' => mb_strtolower($this->input('email')),
            'user_group_id' => $this->input('user_group_id'),
            'password' => Hash::make(uniqid()),
        ];
    }
}
