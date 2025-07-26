import { createRouter, createWebHistory } from "vue-router";
import Welcome from "../pages/Welcome.vue";
import UserLogin from "../pages/UserLogin.vue";
import AdminLogin from "../pages/AdminLogin.vue";
import Posts from "../pages/Posts.vue";
import PostForm from "../pages/PostForm.vue";

const routes = [
    {
        path: "/",
        name: "Welcome",
        component: Welcome,
    },
    {
        path: "/users/login",
        name: "UserLogin",
        component: UserLogin,
    },
    {
        path: "/admins/login",
        name: "AdminLogin",
        component: AdminLogin,
    },
    {
        path: "/posts",
        name: "Posts",
        component: Posts,
    },
    {
        path: "/posts/create",
        name: "PostCreate",
        component: PostForm,
    },
    {
        path: "/posts/:id/edit",
        name: "PostEdit",
        component: PostForm,
        props: true,
    },
    {
        path: "/users",
        name: "UserDashboard",
        component: () => import("../pages/UserDashboard.vue"),
    },
    {
        path: "/admins",
        name: "AdminDashboard",
        component: () => import("../pages/AdminDashboard.vue"),
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
