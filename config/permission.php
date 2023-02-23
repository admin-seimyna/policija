<?php
use \App\Enum\PermissionActionEnum;

return [
    'permissions' => [
        'report' => [
            'actions' => [
                PermissionActionEnum::LIST_ACTION,
                PermissionActionEnum::CREATE_ACTION,
                PermissionActionEnum::EDIT_ACTION,
                PermissionActionEnum::DELETE_ACTION,
            ],
        ],
        'settings' => [
            'actions' => [PermissionActionEnum::LIST_ACTION],
        ],
        'user-group' => [
            'actions' => PermissionActionEnum::values()->toArray(),
            'require' => ['settings' => [PermissionActionEnum::LIST_ACTION]],
        ],
        'user' => [
            'actions' => PermissionActionEnum::values()->toArray(),
            'require' => ['settings' => [PermissionActionEnum::LIST_ACTION]],
        ],
        'shift' => [
            'actions' => PermissionActionEnum::values()->toArray(),
            'require' => ['settings' => [PermissionActionEnum::LIST_ACTION]],
        ],
    ],
];
