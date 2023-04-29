const route = [
  {
    path: "/",
    name: "Layout",
    redirect: "/home",
    component: () => import("@/Layout/index.vue"),
    children: [
      {
        path: "/home",
        name: "home",
        component: () => import("@/views/index.vue"),
      },
        {
            path: "/service_details/:id",
            name: "social-details",
            component: () => import("@/views/social-media/service-details.vue"),
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
                component:() => import("../views/auth/client/login.vue")

            },
            {
                path:'register',
                name:'register',
                component:() => import("../views/auth/client/register.vue")

            },
            {
                path:'confirm_code/:email',
                name:'confirm_code',
                component:() => import("../views/auth/client/confirm_token.vue")

            }
        ]
    }
];

export default route;
