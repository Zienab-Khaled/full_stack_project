<template>
    <div id="app" class="min-h-screen bg-gray-100">
        <!-- Navigation Header -->
        <nav v-if="isAuthenticated" class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <h1 class="text-xl font-bold text-gray-900">
                                Laravel Vue Test
                            </h1>
                        </div>
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <router-link
                                :to="dashboardRoute"
                                class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300"
                            >
                                Dashboard
                            </router-link>
                            <router-link
                                to="/posts"
                                class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300"
                            >
                                Posts
                            </router-link>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <span class="text-gray-700 mr-4">{{
                            userInfo?.name || userInfo?.admin?.name
                        }}</span>
                        <button
                            @click="logout"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                        >
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <router-view />
        </main>
    </div>
</template>

<script>
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

export default {
    name: "App",
    setup() {
        const router = useRouter();
        const userInfo = ref(null);
        const isAuthenticated = ref(false);

        const dashboardRoute = computed(() => {
            const userType = localStorage.getItem("userType");
            return userType === "admin" ? "/admins" : "/users";
        });

        const checkAuth = async () => {
            const token = localStorage.getItem("token");
            const userType = localStorage.getItem("userType");

            if (token && userType) {
                try {
                    const response = await axios.get(`/${userType}/user`);
                    userInfo.value = response.data[userType];
                    isAuthenticated.value = true;
                } catch (error) {
                    console.error("Auth check failed:", error);
                    logout();
                }
            }
        };

        const logout = async () => {
            const token = localStorage.getItem("token");
            const userType = localStorage.getItem("userType");

            if (token && userType) {
                try {
                    await axios.post(`/${userType}/logout`);
                } catch (error) {
                    console.error("Logout error:", error);
                }
            }

            localStorage.removeItem("token");
            localStorage.removeItem("userType");
            userInfo.value = null;
            isAuthenticated.value = false;
            router.push("/users/login");
        };

        onMounted(() => {
            checkAuth();
        });

        return {
            userInfo,
            isAuthenticated,
            dashboardRoute,
            logout,
        };
    },
};
</script>
