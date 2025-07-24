import "./bootstrap";
import { createApp } from "vue";
import { createRouter, createWebHistory } from "vue-router";
import axios from "axios";

// Import components
import App from "./components/App.vue";
import AdminLogin from "./components/auth/AdminLogin.vue";
import UserLogin from "./components/auth/UserLogin.vue";
import AdminDashboard from "./components/admin/AdminDashboard.vue";
import UserDashboard from "./components/user/UserDashboard.vue";
import PostsList from "./components/posts/PostsList.vue";
import PostForm from "./components/posts/PostForm.vue";

// Configure axios
axios.defaults.baseURL = "/api";
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Add token to requests if available
axios.interceptors.request.use((config) => {
    const token = localStorage.getItem("token");
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// Router configuration
const routes = [
    { path: "/", redirect: "/users/login" },
    { path: "/admins/login", component: AdminLogin, name: "admin.login" },
    { path: "/users/login", component: UserLogin, name: "user.login" },
    {
        path: "/admins",
        component: AdminDashboard,
        name: "admin.dashboard",
        meta: { requiresAuth: true, requiresAdmin: true },
    },
    {
        path: "/users",
        component: UserDashboard,
        name: "user.dashboard",
        meta: { requiresAuth: true, requiresUser: true },
    },
    {
        path: "/posts",
        component: PostsList,
        name: "posts.index",
        meta: { requiresAuth: true },
    },
    {
        path: "/posts/create",
        component: PostForm,
        name: "posts.create",
        meta: { requiresAuth: true },
    },
    {
        path: "/posts/:id/edit",
        component: PostForm,
        name: "posts.edit",
        meta: { requiresAuth: true },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Navigation guards
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem("token");
    const userType = localStorage.getItem("userType");

    if (to.meta.requiresAuth && !token) {
        next("/users/login");
    } else if (to.meta.requiresAdmin && userType !== "admin") {
        next("/admins/login");
    } else if (to.meta.requiresUser && userType !== "user") {
        next("/users/login");
    } else {
        next();
    }
});

// Create and mount the app
const app = createApp(App);
app.use(router);

// Global properties
app.config.globalProperties.$axios = axios;

app.mount("#app");
