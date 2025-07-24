<template>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-semibold text-gray-900 mb-4">
                        User Dashboard
                    </h1>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg
                                        class="h-8 w-8 text-blue-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        ></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3
                                        class="text-lg font-medium text-blue-900"
                                    >
                                        My Posts
                                    </h3>
                                    <p
                                        class="text-2xl font-semibold text-blue-600"
                                    >
                                        {{ stats.myPosts }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg
                                        class="h-8 w-8 text-green-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                        ></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3
                                        class="text-lg font-medium text-green-900"
                                    >
                                        Published
                                    </h3>
                                    <p
                                        class="text-2xl font-semibold text-green-600"
                                    >
                                        {{ stats.publishedPosts }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-50 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg
                                        class="h-8 w-8 text-yellow-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                        ></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3
                                        class="text-lg font-medium text-yellow-900"
                                    >
                                        Drafts
                                    </h3>
                                    <p
                                        class="text-2xl font-semibold text-yellow-600"
                                    >
                                        {{ stats.draftPosts }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold text-gray-900">
                            Quick Actions
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <router-link
                                to="/posts"
                                class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                            >
                                <svg
                                    class="h-6 w-6 text-gray-600 mr-3"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                    ></path>
                                </svg>
                                <div>
                                    <h3
                                        class="text-lg font-medium text-gray-900"
                                    >
                                        My Posts
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        View and manage your posts
                                    </p>
                                </div>
                            </router-link>

                            <router-link
                                to="/posts/create"
                                class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                            >
                                <svg
                                    class="h-6 w-6 text-gray-600 mr-3"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                    ></path>
                                </svg>
                                <div>
                                    <h3
                                        class="text-lg font-medium text-gray-900"
                                    >
                                        Create New Post
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        Write and publish a new post
                                    </p>
                                </div>
                            </router-link>
                        </div>
                    </div>

                    <!-- Recent Posts Section -->
                    <div class="mt-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">
                            Recent Posts
                        </h2>
                        <div v-if="recentPosts.length > 0" class="space-y-4">
                            <div
                                v-for="post in recentPosts"
                                :key="post.id"
                                class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors"
                            >
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h3
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            {{ post.title }}
                                        </h3>
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{
                                                post.content.substring(0, 100)
                                            }}...
                                        </p>
                                        <div
                                            class="flex items-center mt-2 space-x-4"
                                        >
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
                                            <span
                                                class="text-sm text-gray-500"
                                                >{{
                                                    formatDate(post.created_at)
                                                }}</span
                                            >
                                        </div>
                                    </div>
                                    <router-link
                                        :to="`/posts/${post.id}/edit`"
                                        class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                                    >
                                        Edit
                                    </router-link>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center py-8 text-gray-500">
                            <p>No posts yet. Create your first post!</p>
                        </div>
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
    name: "UserDashboard",
    setup() {
        const stats = ref({
            myPosts: 0,
            publishedPosts: 0,
            draftPosts: 0,
        });
        const recentPosts = ref([]);

        const fetchStats = async () => {
            try {
                const response = await axios.get("/posts");
                const posts = response.data.posts;

                stats.value.myPosts = posts.length;
                stats.value.publishedPosts = posts.filter(
                    (post) => post.status === "published"
                ).length;
                stats.value.draftPosts = posts.filter(
                    (post) => post.status === "draft"
                ).length;

                // Get recent posts (last 5)
                recentPosts.value = posts.slice(0, 5);
            } catch (error) {
                console.error("Error fetching stats:", error);
            }
        };

        const formatDate = (dateString) => {
            return new Date(dateString).toLocaleDateString();
        };

        onMounted(() => {
            fetchStats();
        });

        return {
            stats,
            recentPosts,
            formatDate,
        };
    },
};
</script>
