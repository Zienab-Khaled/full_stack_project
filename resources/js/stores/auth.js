import { defineStore } from "pinia";
import axios from "axios";

const authAxios = axios.create({
    baseURL: "/api",
    headers: {
        "X-Requested-With": "XMLHttpRequest",
    },
});

export const useAuthStore = defineStore("auth", {
    state: () => ({
        user: null,
        admin: null,
        token: localStorage.getItem("token") || null,
        loading: false,
        error: null,
    }),

    getters: {
        isAuthenticated: (state) => !!state.token,
        isUser: (state) => !!state.user,
        isAdmin: (state) => !!state.admin,
        currentUser: (state) => state.user || state.admin,
    },

    actions: {
        // User Authentication
        async loginUser(credentials) {
            this.loading = true;
            this.error = null;

            try {
                const response = await authAxios.post(
                    "/users/login",
                    credentials
                );
                this.user = response.data.user;
                this.token = response.data.token;
                this.admin = null;

                localStorage.setItem("token", this.token);
                authAxios.defaults.headers.common[
                    "Authorization"
                ] = `Bearer ${this.token}`;

                return response.data;
            } catch (error) {
                this.error = error.response?.data?.message || "Login failed";
                throw error;
            } finally {
                this.loading = false;
            }
        },

        // Admin Authentication
        async loginAdmin(credentials) {
            this.loading = true;
            this.error = null;

            try {
                const response = await authAxios.post(
                    "/admins/login",
                    credentials
                );
                this.admin = response.data.admin;
                this.token = response.data.token;
                this.user = null;

                localStorage.setItem("token", this.token);
                authAxios.defaults.headers.common[
                    "Authorization"
                ] = `Bearer ${this.token}`;

                return response.data;
            } catch (error) {
                this.error = error.response?.data?.message || "Login failed";
                throw error;
            } finally {
                this.loading = false;
            }
        },

        // Logout
        logout() {
            this.user = null;
            this.admin = null;
            this.token = null;
            this.error = null;

            localStorage.removeItem("token");
            delete authAxios.defaults.headers.common["Authorization"];
        },

        // Check authentication status
        async checkAuth() {
            if (!this.token) return false;

            try {
                const response = await authAxios.get("/user");
                if (response.data.user) {
                    this.user = response.data.user;
                    this.admin = null;
                } else if (response.data.admin) {
                    this.admin = response.data.admin;
                    this.user = null;
                }
                return true;
            } catch (error) {
                this.logout();
                return false;
            }
        },

        // Initialize auth on app start
        async init() {
            if (this.token) {
                authAxios.defaults.headers.common[
                    "Authorization"
                ] = `Bearer ${this.token}`;
                await this.checkAuth();
            }
        },
    },
});
