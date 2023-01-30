<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UpdateRequest extends CreateRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'email' => 'required|unique:users,email,' . $this->route('user')->id
        ]);
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
        ];
    }
}
