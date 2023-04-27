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
];

export default admin;
