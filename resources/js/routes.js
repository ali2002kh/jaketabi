import * as VueRouter from "vue-router";
import Home from "./pages/Home";
import Profile from "./pages/Profile";
import Shelf from "./pages/Shelf";
import Friends from "./pages/Friends";
import ProfileBooks from "./pages/ProfileBooks";

import Login from "./pages/auth/Login";
import Signup from "./pages/auth/Signup";

import BookList from "./pages/book/BookList";
import BookComments from "./pages/book/Comments"
import Book from "./pages/book/Book";

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
        path: "/profile/books/:id/:status",
        component: ProfileBooks,
        name:"profileBooks",
    },
    {
        path: "/book/:id",
        component: Book,
        name:"book",
    },
    {
        path: "/book/comments/:id",
        component: BookComments,
        name:"bookComments",
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
    {
        path: "/book-list/:title/:id",
        component: BookList,
        name:"bookList",
    },
    {
        path: "/login",
        component: Login,
        name:"login",
    },
    {
        path: "/signup",
        component: Signup,
        name:"signup",
    },
];

const router = new VueRouter.createRouter({
    history: VueRouter.createWebHistory(),
    routes,
});

export default router;