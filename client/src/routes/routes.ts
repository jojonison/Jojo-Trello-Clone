import {RouteRecordRaw} from "vue-router";

const routes: Array<RouteRecordRaw> = [
    {
        path: "/",
        children: [
            {
                path: "",
                name: 'homepage',
                component: () => import("@/pages/home/index.vue")
            },
            {
                path:"about",
                name: 'about',
                component: () => import("@/pages/about/index.vue")
            },
            {
                path:"todo",
                name:"todo",
                component: () => import("@/pages/tasksdisplay/index.vue")
            },
            {
                path:"logIn",
                name:"logIn",
                component: () => import("@/pages/login/index.vue")
            },
            {
                path: "archives",
                name: "archives",
                component: () => import("@/pages/archives/index.vue")
            }
        ]
    },
]

export default routes;