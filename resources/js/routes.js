import * as VueRouter from "vue-router";
import Home from "./pages/Home";
import Profile from "./pages/Profile";

const routes = [
    {
        path: "/",
        component: Home,
        name:"home",
    },
    {
        path: "/profile",
        component: Profile,
        name:"profile",
    },
];

const router = new VueRouter.createRouter({
    history: VueRouter.createWebHistory(),
    routes,
});

export default router;