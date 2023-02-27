<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'change_password' => 'boolean',
            'password' => 'required_if:change_password,1',
        ];
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $data = [
            'name' => $this->input('name'),
        ];

        if ($this->input('change_password')) {
            $data['password'] = $this->input('password');
        }

        return $data;
    }
}
