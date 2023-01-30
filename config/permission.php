<?php

return [
    'permissions' => [
        'user-group' => \App\Enum\PermissionActionEnum::values()->toArray(),
        'user' => \App\Enum\PermissionActionEnum::values()->toArray(),
        'report' => \App\Enum\PermissionActionEnum::values()->toArray(),
    ],
];
