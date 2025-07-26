import "./bootstrap";
import { createApp } from "vue";
import { createPinia } from "pinia";
import router from "./router";

// Configure axios
axios.defaults.baseURL = "";
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Add token to requests
axios.interceptors.request.use((config) => {
    const token = localStorage.getItem("token");
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// Create Vue app
const app = createApp({
    template: '<div id="app"><router-view></router-view></div>',
});

// Use Pinia and Router
const pinia = createPinia();
app.use(pinia);
app.use(router);

// Mount the app
app.mount("#app");
