<template>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Header -->
                    <div class="mb-6">
                        <h1 class="text-2xl font-semibold text-gray-900">
                            {{ isEditing ? "Edit Post" : "Create New Post" }}
                        </h1>
                        <p class="mt-1 text-sm text-gray-600">
                            {{
                                isEditing
                                    ? "Update your post content and settings."
                                    : "Write and publish your new post."
                            }}
                        </p>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submitForm" class="space-y-6">
                        <!-- Title -->
                        <div>
                            <label
                                for="title"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Title *
                            </label>
                            <input
                                id="title"
                                v-model="form.title"
                                type="text"
                                required
                                maxlength="255"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                :class="{ 'border-red-500': errors.title }"
                                placeholder="Enter post title..."
                            />
                            <div
                                v-if="errors.title"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ errors.title }}
                            </div>
                            <div class="mt-1 text-sm text-gray-500">
                                {{ form.title.length }}/255 characters
                            </div>
                        </div>

                        <!-- Content -->
                        <div>
                            <label
                                for="content"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Content *
                            </label>
                            <textarea
                                id="content"
                                v-model="form.content"
                                rows="8"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                :class="{ 'border-red-500': errors.content }"
                                placeholder="Write your post content here..."
                            ></textarea>
                            <div
                                v-if="errors.content"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ errors.content }}
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label
                                for="status"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Status *
                            </label>
                            <select
                                id="status"
                                v-model="form.status"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                :class="{ 'border-red-500': errors.status }"
                            >
                                <option value="">Select status</option>
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                            <div
                                v-if="errors.status"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ errors.status }}
                            </div>
                            <div class="mt-1 text-sm text-gray-500">
                                <span
                                    v-if="form.status === 'draft'"
                                    class="text-yellow-600"
                                >
                                    Draft posts are saved but not visible to
                                    others.
                                </span>
                                <span
                                    v-else-if="form.status === 'published'"
                                    class="text-green-600"
                                >
                                    Published posts are visible to everyone.
                                </span>
                            </div>
                        </div>

                        <!-- Error Messages -->
                        <div
                            v-if="generalError"
                            class="bg-red-50 border border-red-200 rounded-md p-4"
                        >
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg
                                        class="h-5 w-5 text-red-400"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-800">
                                        {{ generalError }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div
                            class="flex justify-end space-x-4 pt-6 border-t border-gray-200"
                        >
                            <router-link
                                to="/posts"
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Cancel
                            </router-link>
                            <button
                                type="submit"
                                :disabled="loading"
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
                            >
                                <span v-if="loading" class="flex items-center">
                                    <svg
                                        class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
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
                                    {{
                                        isEditing
                                            ? "Updating..."
                                            : "Creating..."
                                    }}
                                </span>
                                <span v-else>
                                    {{
                                        isEditing
                                            ? "Update Post"
                                            : "Create Post"
                                    }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";

export default {
    name: "PostForm",
    setup() {
        const route = useRoute();
        const router = useRouter();
        const loading = ref(false);
        const generalError = ref("");
        const errors = ref({});

        const form = ref({
            title: "",
            content: "",
            status: "",
        });

        const isEditing = computed(() => {
            return route.params.id !== undefined;
        });

        const fetchPost = async () => {
            if (!isEditing.value) return;

            try {
                const response = await axios.get(`/posts/${route.params.id}`);
                const post = response.data.post;

                form.value = {
                    title: post.title,
                    content: post.content,
                    status: post.status,
                };
            } catch (error) {
                console.error("Error fetching post:", error);
                generalError.value = "Error loading post. Please try again.";
            }
        };

        const submitForm = async () => {
            loading.value = true;
            generalError.value = "";
            errors.value = {};

            try {
                if (isEditing.value) {
                    await axios.put(`/posts/${route.params.id}`, form.value);
                } else {
                    await axios.post("/posts", form.value);
                }

                router.push("/posts");
            } catch (error) {
                if (error.response?.data?.errors) {
                    errors.value = error.response.data.errors;
                } else if (error.response?.data?.message) {
                    generalError.value = error.response.data.message;
                } else {
                    generalError.value = "An error occurred. Please try again.";
                }
            } finally {
                loading.value = false;
            }
        };

        onMounted(() => {
            fetchPost();
        });

        return {
            form,
            loading,
            errors,
            generalError,
            isEditing,
            submitForm,
        };
    },
};
</script>
