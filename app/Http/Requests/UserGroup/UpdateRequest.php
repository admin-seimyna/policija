<?php

namespace App\Http\Requests\UserGroup;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'title' => [
                'required',
                Rule::unique('user_groups')->ignore($this->route('userGroup')->id)->whereNull('deleted_at')
            ],
        ]);
    }
}
