import * as VueRouter from "vue-router";
import Home from "./pages/Home";
import Profile from "./pages/Profile";
import Book from "./pages/Book";
import Shelf from "./pages/Shelf";
import Friends from "./pages/Friends";

const routes = [
    {
        path: "/",
        component: Home,
        name:"home",
    },
    {
        path: "/profile/:id",
        component: Profile,
        name:"profile",
    },
    {
        path: "/book/:id",
        component: Book,
        name:"book",
    },
    {
        path: "/shelf/:id",
        component: Shelf,
        name:"shelf",
    },
    {
        path: "/friends",
        component: Friends,
        name:"friends",
    },
];

const router = new VueRouter.createRouter({
    history: VueRouter.createWebHistory(),
    routes,
});

export default router;