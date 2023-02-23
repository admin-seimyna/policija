<?php

namespace App\Enum;

class PermissionActionEnum extends Enum
{
    const CREATE_ACTION = 'create';
    const EDIT_ACTION = 'edit';
    const DELETE_ACTION = 'delete';
    const VIEW_ACTION = 'view';
    const LIST_ACTION = 'list';

    /**
     * @return array
     */
    public static function sorted(): array
    {
        $sort = [
            static::LIST_ACTION,
            static::VIEW_ACTION,
            static::CREATE_ACTION,
            static::EDIT_ACTION,
            static::DELETE_ACTION,
        ];

        return static::values()->sort(function ($a, $b) use ($sort) {
            return array_search($a, $sort) > array_search($b, $sort);
        })->values()->toArray();
    }
}
