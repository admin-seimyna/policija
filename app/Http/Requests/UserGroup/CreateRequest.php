<?php

namespace App\Http\Requests\UserGroup;

use App\Enum\PermissionActionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

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
            'title' => 'required|unique:user_groups',
            'permissions' => 'array',
            'permissions.*.name' => 'in:' . implode(',', array_keys(config('permission.permissions'))),
            'permissions.*.permissions' => 'required|array',
            'permissions.*.permissions.*.name' => 'required|in:' . PermissionActionEnum::values()->join(','),
            'permissions.*.permissions.*.value' => 'required|bool',
        ];
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            'title' => $this->input('title')
        ];
    }

    /**
     * @return Collection
     */
    public function getPermissions(): Collection
    {
        $permissions = collect();
        collect($this->input('permissions'))->each(static function ($group) use (&$permissions) {
            collect($group['permissions'])->each(static function ($permission) use (&$permissions, $group) {
                $permissions->push([
                    'group' => $group['name'],
                    'action' => $permission['name'],
                    'value' => $permission['value']
                ]);
            });
        });

        return $permissions;
    }
}
