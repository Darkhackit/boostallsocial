const admin = [
    {
        path: "/",
        name: "AdminLayout",
        component: () => import("@/Layout/AdminLayout.vue"),
        children: [
            {
                path: "/home",
                name: "home",
                component: () => import("@/views/admin/providers.vue"),
            },
            {
                path: "/provider",
                name: "provider",
                component: () => import("@/views/admin/providers.vue"),
            },
            {
                path: "/social-media",
                name: "social-media",
                component: () => import("@/views/admin/social-media.vue"),
            },
            {
                path: "/target-country",
                name: "target-country",
                component: () => import("@/views/admin/target-country.vue"),
            },
            {
                path: "/chat",
                name: "chat",
                component: () => import("@/views/index.vue"),
            },
            {
                path: "/profile",
                name: "profile",
                component: () => import("@/views/index.vue"),
            },
            {
                path: "/notifications",
                name: "notifications",
                component: () => import("@/views/index.vue"),
            },
        ],
    },
    {
        path: '/auth',
        component:() => import("../Layout/AuthLayout.vue"),
        meta:{requireAuth:true},
        children: [
            {
                path:'login',
                name:'login',
                component:() => import("../views/auth/admin/login.vue")

            },
            {
                path:'register',
                name:'register',
                component:() => import("../views/auth/admin/login.vue")

            },
            {
                path:'confirm_code/:email',
                name:'confirm_code',
                component:() => import("../views/auth/admin/login.vue")

            },
            {
                path:'forget-password',
                name:'forget_password',
                component:() => import("../views/auth/admin/login.vue")

            },
            {
                path:'change-password',
                name:'change_password',
                component:() => import("../views/auth/admin/login.vue")

            }
        ]
    }
];

export default admin;
