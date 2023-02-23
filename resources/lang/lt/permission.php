<?php

return [
    'title' => [
        'permissions' => 'Teisės',
        'action' => [
            'list' => 'Sąrašas',
            'view' => 'Peržiūra',
            'create' => 'Kurti',
            'edit' => 'Redaguoti',
            'delete' => 'Šalinti',
        ],

        'group' => [
            'settings' => 'Nustatymai',
            'report' => 'Ataskaitos',
            'user-group' => 'Naudotojų grupės',
            'user' => 'Naudotojai',
            'shift' => 'Pamainos',
        ],
    ],

    'message' => [
        'report' => [
            'list' => 'Naudotojas gali matyti kitų naudotojų ataskaitas.',
            'create' => 'Naudotojas gali kurti kitiems naudotojams ataskaitas.',
            'edit' => 'Naudotojas gali redaguoti kitų naudotojų ataskaitas.',
            'delete' => 'Naudotojas gali šalinti kitų naudotojų ataskaitas.',
        ],
        'blocked_by_other_permission' => 'Nėra galimybės suteikti šią teisę, nes ji priklauso nuo aukščiau esančios teisių grupės',
    ],
];
