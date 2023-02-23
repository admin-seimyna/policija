<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
            'date' => 'required|date:Y-m-d',
            'user_id' => 'required|exists:users,id',
            'shift_id' => 'required|exists:shifts,id',
        ];

        if (!Gate::check('permission', [Auth::user(), 'report.create'])) {
            unset($rules['user_id']);
        }
        return $rules;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $data = [
            'date' => $this->input('date'),
            'user_id' => $this->getUserId(),
            'shift_id' => $this->input('shift_id'),
            'events_count' => (int)$this->input('events_count', 0),
            'processed_events_count' => (int)$this->input('processed_events_count', 0),
            'an_count' => (int)$this->input('an_count', 0),
            'traffic_events_count' => (int)$this->input('traffic_events_count', 0),
            'unprocessed_events_count' => (int)$this->input('unprocessed_events_count', 0),
            'comment' => $this->input('comment', null),
        ];

        return $data;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        $user = Auth::user();
        if (!Gate::check('permission', [Auth::user(), 'report.create'])) {
            return $user->id;
        }
        return (int)$this->input('user_id');
    }
}
