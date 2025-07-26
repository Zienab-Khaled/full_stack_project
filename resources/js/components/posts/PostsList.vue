<template>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Search and Filters -->
                    <div class="mb-6 space-y-4">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1">
                                <input
                                    v-model="search"
                                    type="text"
                                    placeholder="Search posts..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    @input="debounceSearch"
                                />
                            </div>
                            <div class="sm:w-48">
                                <select
                                    v-model="statusFilter"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    @change="fetchPosts"
                                >
                                    <option value="">All Status</option>
                                    <option value="published">Published</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Posts List -->
                    <div v-if="loading" class="text-center py-8">
                        <div
                            class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"
                        ></div>
                        <p class="mt-2 text-gray-600">Loading posts...</p>
                    </div>

                    <div
                        v-else-if="posts.length === 0"
                        class="text-center py-8"
                    >
                        <p class="text-gray-500">No posts found.</p>
                    </div>

                    <div v-else class="space-y-4">
                        <div
                            v-for="post in posts"
                            :key="post.id"
                            class="border border-gray-200 rounded-lg p-6 hover:bg-gray-50 transition-colors"
                        >
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div
                                        class="flex items-center space-x-2 mb-2"
                                    >
                                        <h3
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            {{ post.title }}
                                        </h3>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                            :class="
                                                post.status === 'published'
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-yellow-100 text-yellow-800'
                                            "
                                        >
                                            {{ post.status }}
                                        </span>
                                    </div>
                                    <p class="text-gray-600 mb-3">
                                        {{ post.content.substring(0, 200) }}...
                                    </p>
                                    <div
                                        class="flex items-center text-sm text-gray-500 space-x-4"
                                    >
                                        <span
                                            >By:
                                            {{
                                                post.author?.name || "Unknown"
                                            }}</span
                                        >
                                        <span>{{
                                            formatDate(post.created_at)
                                        }}</span>
                                        <span
                                            v-if="
                                                post.updated_at !==
                                                post.created_at
                                            "
                                        >
                                            Updated:
                                            {{ formatDate(post.updated_at) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex space-x-2 ml-4">
                                    <a
                                        :href="`/posts/${post.id}/edit`"
                                        class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                                    >
                                        Edit
                                    </a>
                                    <button
                                        @click="deletePost(post.id)"
                                        class="text-red-600 hover:text-red-900 text-sm font-medium"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div
                        v-if="pagination.last_page > 1"
                        class="mt-6 flex justify-center"
                    >
                        <nav class="flex space-x-2">
                            <button
                                v-for="page in pagination.last_page"
                                :key="page"
                                @click="goToPage(page)"
                                :class="[
                                    'px-3 py-2 text-sm font-medium rounded-md',
                                    page === pagination.current_page
                                        ? 'bg-indigo-600 text-white'
                                        : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50',
                                ]"
                            >
                                {{ page }}
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from "vue";
import axios from "axios";

export default {
    name: "PostsList",
    setup() {
        const posts = ref([]);
        const loading = ref(false);
        const search = ref("");
        const statusFilter = ref("");
        const pagination = ref({
            current_page: 1,
            last_page: 1,
            per_page: 10,
            total: 0,
        });
        let searchTimeout = null;

        const fetchPosts = async (page = 1) => {
            loading.value = true;
            try {
                const params = {
                    page,
                    per_page: 10,
                };

                if (search.value) {
                    params.search = search.value;
                }

                if (statusFilter.value) {
                    params.status = statusFilter.value;
                }

                const response = await axios.get("/posts", { params });
                posts.value = response.data.posts;
                pagination.value = response.data.pagination;
            } catch (error) {
                console.error("Error fetching posts:", error);
            } finally {
                loading.value = false;
            }
        };

        const debounceSearch = () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                fetchPosts(1);
            }, 300);
        };

        const goToPage = (page) => {
            fetchPosts(page);
        };

        const deletePost = async (postId) => {
            if (!confirm("Are you sure you want to delete this post?")) {
                return;
            }

            try {
                await axios.delete(`/posts/${postId}`);
                await fetchPosts(pagination.value.current_page);
            } catch (error) {
                console.error("Error deleting post:", error);
                alert("Error deleting post. Please try again.");
            }
        };

        const formatDate = (dateString) => {
            return new Date(dateString).toLocaleDateString();
        };

        onMounted(() => {
            fetchPosts();
        });

        return {
            posts,
            loading,
            search,
            statusFilter,
            pagination,
            fetchPosts,
            debounceSearch,
            goToPage,
            deletePost,
            formatDate,
        };
    },
};
</script>
