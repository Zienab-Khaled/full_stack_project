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
                    <form @submit.prevent="submitForm" class="space-y-6" enctype="multipart/form-data">
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
                                :class="{ 'border-red-500': errors.title && errors.title.length > 0 }"
                                placeholder="Enter post title..."
                            />
                            <div
                                v-if="errors.title && errors.title.length > 0"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ Array.isArray(errors.title) ? errors.title[0] : errors.title }}
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
                                :class="{ 'border-red-500': errors.content && errors.content.length > 0 }"
                                placeholder="Write your post content here..."
                            ></textarea>
                            <div
                                v-if="errors.content && errors.content.length > 0"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ Array.isArray(errors.content) ? errors.content[0] : errors.content }}
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div>
                            <label
                                for="image"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Featured Image
                            </label>
                            <div class="flex items-center space-x-4">
                                <input
                                    id="image"
                                    ref="imageInput"
                                    type="file"
                                    accept="image/*"
                                    @change="handleImageChange"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                />
                                <button
                                    v-if="form.image || imagePreview"
                                    type="button"
                                    @click="removeImage"
                                    class="px-3 py-2 text-sm text-red-600 hover:text-red-800 font-medium"
                                >
                                    Remove
                                </button>
                            </div>
                            <div
                                v-if="errors.image && errors.image.length > 0"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ Array.isArray(errors.image) ? errors.image[0] : errors.image }}
                            </div>
                            <div class="mt-1 text-sm text-gray-500">
                                Upload a featured image for your post (optional)
                            </div>
                            <!-- Image Preview -->
                            <div v-if="imagePreview" class="mt-3">
                                <img
                                    :src="imagePreview"
                                    alt="Preview"
                                    class="w-32 h-32 object-cover rounded-lg border border-gray-300"
                                />
                            </div>
                            <!-- Current Image (for edit mode) -->
                            <div v-else-if="form.current_image" class="mt-3">
                                <img
                                    :src="form.current_image"
                                    alt="Current image"
                                    class="w-32 h-32 object-cover rounded-lg border border-gray-300"
                                />
                                <p class="mt-1 text-sm text-gray-500">Current image</p>
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
                                :class="{ 'border-red-500': errors.status && errors.status.length > 0 }"
                            >
                                <option value="">Select status</option>
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                            <div
                                v-if="errors.status && errors.status.length > 0"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ Array.isArray(errors.status) ? errors.status[0] : errors.status }}
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
                            <a
                                href="/posts"
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Cancel
                            </a>
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
import axios from "axios";

export default {
    name: "PostForm",
    setup() {
        const loading = ref(false);
        const generalError = ref("");
        const errors = ref({});
        const imageInput = ref(null);
        const imagePreview = ref(null);

        const form = ref({
            title: "",
            content: "",
            status: "",
            image: null,
            current_image: null,
        });

        const isEditing = computed(() => {
            // Check if we're in edit mode by looking for post ID in the URL
            const urlParts = window.location.pathname.split('/');
            return urlParts.includes('edit');
        });

        const getPostId = () => {
            const urlParts = window.location.pathname.split('/');
            const editIndex = urlParts.indexOf('edit');
            if (editIndex > 0) {
                return urlParts[editIndex - 1];
            }
            return null;
        };

        const fetchPost = async () => {
            if (!isEditing.value) return;

            const postId = getPostId();
            if (!postId) return;

            console.log('Fetching post with ID:', postId); // Debug log

            try {
                const response = await axios.get(`/posts/${postId}`);
                const post = response.data.post;

                console.log('Fetched post:', post); // Debug log

                // Clear any existing errors when loading data
                errors.value = {};
                generalError.value = "";

                form.value = {
                    title: post.title,
                    content: post.content,
                    status: post.status,
                    image: null,
                    current_image: post.image_url || null,
                };
            } catch (error) {
                console.error("Error fetching post:", error);
                generalError.value = "Error loading post. Please try again.";
            }
        };

        const handleImageChange = (event) => {
            const file = event.target.files[0];
            if (file) {
                form.value.image = file;
                // Create preview
                const reader = new FileReader();
                reader.onload = (e) => {
                    imagePreview.value = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        };

        const removeImage = () => {
            form.value.image = null;
            imagePreview.value = null;
            if (imageInput.value) {
                imageInput.value.value = '';
            }
        };

        const submitForm = async () => {
            loading.value = true;
            generalError.value = "";
            errors.value = {};

            try {
                const formData = new FormData();
                formData.append('title', form.value.title);
                formData.append('content', form.value.content);
                formData.append('status', form.value.status);

                if (form.value.image) {
                    formData.append('image', form.value.image);
                }

                if (isEditing.value) {
                    const postId = getPostId();
                    await axios.put(`/posts/${postId}`, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                } else {
                    await axios.post("/posts", formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                }

                window.location.href = "/posts";
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
            imageInput,
            imagePreview,
            handleImageChange,
            removeImage,
            submitForm,
        };
    },
};
</script>
