<template>
    <form @submit.prevent="login" class="space-y-6">
        <!-- Email Field -->
        <div class="space-y-2">
            <div class="relative">
                <div
                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"
                >
                    <svg
                        class="h-5 w-5 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"
                        ></path>
                    </svg>
                </div>
                <input
                    id="email"
                    v-model="form.email"
                    name="email"
                    type="email"
                    required
                    class="block w-full pl-12 pr-4 py-4 bg-white text-gray-900 placeholder-gray-500 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition-all duration-200"
                    placeholder="admin@reframeplus.com"
                />
            </div>
        </div>

        <!-- Password Field -->
        <div class="space-y-2">
            <div class="relative">
                <div
                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"
                >
                    <svg
                        class="h-5 w-5 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                        ></path>
                    </svg>
                </div>
                <input
                    id="password"
                    v-model="form.password"
                    name="password"
                    type="password"
                    required
                    class="block w-full pl-12 pr-4 py-4 bg-white text-gray-900 placeholder-gray-500 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition-all duration-200"
                    placeholder="Admin Password"
                />
            </div>
        </div>

        <!-- Remember Me and Forgot Password -->
        <div class="flex items-center justify-between">
            <label class="flex items-center">
                <input
                    type="checkbox"
                    class="w-4 h-4 text-slate-600 border-gray-300 rounded focus:ring-slate-500 focus:ring-opacity-50"
                />
                <span class="ml-2 text-sm text-gray-700 font-medium"
                    >Remember me</span
                >
            </label>
            <a
                href="#"
                class="text-sm text-gray-600 hover:text-slate-600 transition-colors duration-200"
            >
                Forgot Password?
            </a>
        </div>

        <!-- Error Message -->
        <div
            v-if="error"
            class="bg-red-50 border border-red-200 rounded-xl p-4"
        >
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg
                        class="h-5 w-5 text-red-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        ></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700 font-medium">
                        {{ error }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Login Button -->
        <button
            type="submit"
            :disabled="loading"
            class="w-full py-4 px-6 bg-slate-800 text-white font-semibold rounded-xl shadow-lg hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-opacity-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-xl"
        >
            <span
                v-if="loading"
                class="flex items-center justify-center"
            >
                <svg
                    class="animate-spin h-5 w-5 mr-2"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                    ></circle>
                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                    ></path>
                </svg>
                Authenticating...
            </span>
            <span v-else>ACCESS ADMIN PANEL</span>
        </button>

        <!-- User Login Link -->
        <div class="text-center pt-4 border-t border-gray-200">
            <a
                href="/users/login"
                class="inline-flex items-center text-sm text-gray-700 hover:text-slate-600 transition-colors duration-200 font-medium"
            >
                <svg
                    class="w-4 h-4 mr-2"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                    ></path>
                </svg>
                Login as Regular User
            </a>
        </div>

        <!-- Copyright -->
        <div class="text-center pt-4">
            <p class="text-xs text-gray-500">
                © 2025 {{ config('app.name', 'Laravel Vue Blog') }}. Administrative access only.
            </p>
        </div>
    </form>
</template>

<script>
import { ref } from "vue";
import axios from "axios";

export default {
    name: "AdminLogin",
    setup() {
        const loading = ref(false);
        const error = ref("");
        const form = ref({
            email: "",
            password: "",
        });

        const login = async () => {
            loading.value = true;
            error.value = "";

            try {
                const response = await axios.post("/admins/login", form.value);

                localStorage.setItem("token", response.data.token);
                localStorage.setItem("userType", "admin");

                window.location.href = "/admins";
            } catch (err) {
                if (err.response?.data?.message) {
                    error.value = err.response.data.message;
                } else if (err.response?.data?.errors?.email) {
                    error.value = err.response.data.errors.email[0];
                } else {
                    error.value = "Login failed. Please try again.";
                }
            } finally {
                loading.value = false;
            }
        };

        return {
            form,
            loading,
            error,
            login,
        };
    },
};
</script>

<style scoped>
/* Smooth transitions */
* {
    transition: all 0.2s ease-in-out;
}
</style>
