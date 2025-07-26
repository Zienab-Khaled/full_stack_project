<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center space-x-6">
            <router-link to="/" class="text-xl font-bold text-gray-900">
              User Dashboard
            </router-link>
            <router-link to="/" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
              Home
            </router-link>
          </div>
          <div class="flex items-center space-x-4">
            <span class="text-sm text-gray-600">
              Welcome, {{ authStore.currentUser?.name || 'User' }}
            </span>
            <button
              @click="logout"
              class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium"
            >
              Logout
            </button>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
              <h2 class="text-2xl font-bold text-gray-900">My Posts</h2>
              <router-link to="/posts/create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create New Post
              </router-link>
            </div>

            <!-- Posts List -->
            <div v-if="loading" class="flex justify-center items-center py-12">
              <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
            </div>

            <div v-else-if="userPosts.length === 0" class="text-center py-12">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">No posts yet</h3>
              <p class="mt-1 text-sm text-gray-500">Get started by creating your first post.</p>
            </div>

            <div v-else class="space-y-6">
              <div
                v-for="post in userPosts"
                :key="post.id"
                class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden"
              >
                <div class="flex">
                  <!-- Post Image -->
                  <div class="w-48 h-48 flex-shrink-0">
                                                <img
                              v-if="post.image"
                              :src="post.image_url || `/storage/${post.image}`"
                              :alt="post.title"
                              class="w-full h-full object-cover"
                            />
                                                <div
                              v-else
                              class="w-full h-full bg-gray-100 flex items-center justify-center"
                            >
                              <div class="text-center">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-xs text-gray-400 font-medium">No Image</p>
                              </div>
                            </div>
                  </div>

                  <!-- Post Content -->
                  <div class="flex-1 p-6">
                    <div class="flex items-center space-x-2 mb-2">
                      <span
                        :class="{
                          'bg-green-100 text-green-800': post.status === 'published',
                          'bg-yellow-100 text-yellow-800': post.status === 'draft'
                        }"
                        class="px-2 py-1 text-xs font-medium rounded-full"
                      >
                        {{ post.status === 'published' ? 'Published' : 'Draft' }}
                      </span>
                      <span class="text-sm text-gray-500">
                        {{ formatDate(post.created_at) }}
                      </span>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                      {{ post.title }}
                    </h3>

                    <p class="text-gray-600 mb-4 line-clamp-3">
                      {{ post.content }}
                    </p>

                    <div class="flex items-center justify-end space-x-3">
                      <router-link
                        :to="`/posts/${post.id}/edit`"
                        class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                        title="Edit Post"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                      </router-link>
                      <button
                        @click="deletePost(post.id)"
                        class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors duration-200"
                        title="Delete Post"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import axios from 'axios'

export default {
  name: 'UserDashboard',
  setup() {
    const router = useRouter()
    const authStore = useAuthStore()

    const userPosts = ref([])
    const loading = ref(true)

    const fetchUserPosts = async () => {
      try {
        loading.value = true
        const response = await axios.get('/api/posts')
        // Temporarily show all posts since auth store is disabled
        userPosts.value = response.data.posts || []
      } catch (error) {
        console.log('Error fetching user posts:', error)
        userPosts.value = []
      } finally {
        loading.value = false
      }
    }

    const deletePost = async (postId) => {
      if (!confirm('Are you sure you want to delete this post?')) return

      try {
        await axios.delete(`/api/posts/${postId}`)
        userPosts.value = userPosts.value.filter(post => post.id !== postId)
      } catch (error) {
        console.log('Error deleting post:', error)
        alert('Error deleting post')
      }
    }

    const logout = () => {
      authStore.logout()
      router.push('/')
    }

    const formatDate = (dateString) => {
      return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      })
    }

    onMounted(() => {
      // Temporarily disable auth check since auth store is disabled
      // if (!authStore.isUser) {
      //   router.push('/users/login')
      //   return
      // }
      fetchUserPosts()
    })

    return {
      authStore,
      userPosts,
      loading,
      deletePost,
      logout,
      formatDate
    }
  }
}
</script>

<style scoped>
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
