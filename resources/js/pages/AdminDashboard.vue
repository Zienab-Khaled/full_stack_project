<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center space-x-6">
            <router-link to="/" class="text-xl font-bold text-gray-900">
              Admin Dashboard
            </router-link>
            <router-link to="/" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
              Home
            </router-link>
          </div>
          <div class="flex items-center space-x-4">
            <span class="text-sm text-gray-600">
              Welcome, {{ authStore.currentUser?.name || 'Admin' }}
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
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Admin Dashboard</h2>

            <!-- Post Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
              <!-- Total Posts -->
              <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                  </div>
                  <div class="ml-4">
                    <p class="text-sm font-medium text-blue-600">Total Posts</p>
                                                <p class="text-2xl font-bold text-blue-900">{{ stats.total }}</p>
                  </div>
                </div>
              </div>

              <!-- Published Posts -->
              <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                  </div>
                  <div class="ml-4">
                    <p class="text-sm font-medium text-green-600">Published Posts</p>
                                                <p class="text-2xl font-bold text-green-900">{{ stats.published }}</p>
                  </div>
                </div>
              </div>

              <!-- Draft Posts -->
              <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                  <div class="ml-4">
                    <p class="text-sm font-medium text-yellow-600">Draft Posts</p>
                                                <p class="text-2xl font-bold text-yellow-900">{{ stats.draft }}</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Quick Actions -->
            <div>
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- View All Posts -->
                <router-link to="/posts" class="block">
                  <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center">
                      <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                      </div>
                      <div class="ml-4">
                        <h4 class="text-lg font-medium text-gray-900">View All Posts</h4>
                        <p class="text-sm text-gray-600">Manage and view all posts in the system</p>
                      </div>
                    </div>
                  </div>
                </router-link>

                <!-- Create New Post -->
                <router-link to="/posts/create" class="block">
                  <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center">
                      <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                      </div>
                      <div class="ml-4">
                        <h4 class="text-lg font-medium text-gray-900">Create New Post</h4>
                        <p class="text-sm text-gray-600">Add a new post to the system</p>
                      </div>
                    </div>
                  </div>
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import axios from 'axios'

export default {
  name: 'AdminDashboard',
  setup() {
    const router = useRouter()
    const authStore = useAuthStore()

    const stats = ref({
      total: 0,
      published: 0,
      draft: 0
    })
    const loading = ref(true)

    const fetchStats = async () => {
      try {
        loading.value = true
        const token = localStorage.getItem('token')
        const headers = token ? { Authorization: `Bearer ${token}` } : {}

        const response = await axios.get('/api/posts/stats', { headers })
        stats.value = response.data.stats || { total: 0, published: 0, draft: 0 }
        console.log('Stats loaded:', stats.value)
      } catch (error) {
        console.log('Error fetching stats:', error)
        stats.value = { total: 0, published: 0, draft: 0 }
      } finally {
        loading.value = false
      }
    }

    const logout = () => {
      authStore.logout()
      router.push('/')
    }

    onMounted(() => {
      // Temporarily disable auth check since auth store is disabled
      // if (!authStore.isAdmin) {
      //   router.push('/admins/login')
      //   return
      // }
      fetchStats()
    })

    return {
      authStore,
      stats,
      loading,
      logout
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
