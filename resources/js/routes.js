export default [
    {
        path: '/login',
        name: 'login',
        meta: { public: true },
        component: () => import('@/Components/Auth/Login/Index'),
    },{
        path: '/',
        name: 'dashboard',
        meta: {
            payload: '/dashboard/payload',
        },
        component: () => import('@/Components/Dashboard/Index'),
    }, {
        path: '/settings',
        name: 'settings',
        component: () => import('@/Components/Settings/Index'),
        children: [
            {
                path: '/settings/user-group/list',
                name: 'settings.user-group.list',
                meta: {
                    payload: '/settings/user-group/payload',
                },
                component: () => import('@/Components/Settings/UserGroup/Index')
            },
            {
                path: '/settings/user/list',
                name: 'settings.user.list',
                meta: {
                    payload: '/settings/user/payload',
                },
                component: () => import('@/Components/Settings/Users/Index')
            },{
                path: '/settings/shift/list',
                name: 'settings.shift.list',
                component: () => import('@/Components/Settings/Shift/Index')
            }
        ]
    }
];
