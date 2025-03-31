import * as VueRouter from "vue-router";
import Home from "./pages/Home";
import Profile from "./pages/Profile";
import ProfileBooks from "./pages/ProfileBooks";

import Login from "./pages/auth/Login";
import Signup from "./pages/auth/Signup";

import BookList from "./pages/book/BookList";
import Book from "./pages/book/Book";

import Shelf from "./pages/shelf/Shelf";
import ShelfList from "./pages/shelf/ShelfList";

import Admin from "./pages/admin/Admin";
import Dashboard from "./pages/admin/Dashboard";
import Books from "./pages/admin/Books";
import CreateBook from "./pages/admin/CreateBook";
import EditBook from "./pages/admin/EditBook";
import PublisherAdmins from "./pages/admin/PublisherAdmins";
import Publishers from "./pages/admin/Publishers";
import Admins from "./pages/admin/Admins";
import Register from "./pages/auth/Register";


const routes = [
    {
        path: "/admin",
        component: Admin,
        children: [
            // publisher admin
            {
                path: "",
                component: Dashboard,
                name: "dashboard",
            },
            {
                path: "books",
                component: Books,
                name: "books",
            },
            {
                path: "create-book",
                component: CreateBook,
                name: "create-book",
            },
            {
                path: "edit-book/:id",
                component: EditBook,
                name: "edit-book",
            },
            // publisher super admin
            {
                path: "publisher-admins",
                component: PublisherAdmins,
                name: "publisher-admins",
            },
            // admin
            {
                path: "publishers",
                component: Publishers,
                name: "publishers",
            },
            // super admin
            {
                path: "admins",
                component: Admins,
                name: "admins",
            },
        ]
    },
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
        path: "/shelf/:id",
        component: Shelf,
        name:"shelf",
    },
    {
        path: "/shelves/:id",
        component: ShelfList,
        name:"shelfList",
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
    {
        path: "/register",
        component: Register,
        name:"register",
    },
];

const router = new VueRouter.createRouter({
    history: VueRouter.createWebHistory(),
    routes,
});

export default router;
