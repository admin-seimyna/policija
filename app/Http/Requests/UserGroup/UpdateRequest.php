<?php

namespace App\Http\Requests\UserGroup;

use Illuminate\Foundation\Http\FormRequest;

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
            'title' => 'required|unique:user_groups,id,' . $this->route('userGroup')->id,
        ]);
    }
}
