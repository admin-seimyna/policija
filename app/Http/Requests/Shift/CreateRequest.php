<?php

namespace App\Http\Requests\Shift;

use Illuminate\Foundation\Http\FormRequest;

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
            'title' => 'required',
            'time_from' => 'required',
            'time_to' => 'required',
        ];
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            'title' => $this->input('title'),
            'time_from' => $this->input('time_from'),
            'time_to' => $this->input('time_to'),
        ];
    }
}
