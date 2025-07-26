<template>
  <div class="min-h-screen bg-gradient-to-br from-sky-200 via-indigo-200 to-violet-300 overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>

    <div class="relative min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
      <div class="max-w-md w-full space-y-8">
        <!-- Logo and Title -->
        <div class="text-center">
          <div class="flex justify-center mb-6">
            <div class="w-20 h-20 bg-gradient-to-r from-slate-800 to-gray-700 rounded-3xl flex items-center justify-center shadow-2xl">
              <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
              </svg>
            </div>
          </div>
          <h1 class="text-4xl font-bold text-gray-900 mb-4">
            Welcome to Our Platform
          </h1>
          <p class="text-lg text-gray-700">
            Choose your login type to get started
          </p>
        </div>

        <!-- Login Cards -->
        <div class="space-y-6">
          <!-- User Login Card -->
          <router-link to="/users/login" class="block">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/30 p-8 hover:bg-white/90 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-3xl">
              <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center">
                  <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                </div>
                <div class="flex-1">
                  <h3 class="text-xl font-semibold text-gray-900">Sign in as User</h3>
                  <p class="text-gray-600">Access your personal dashboard</p>
                </div>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </div>
            </div>
          </router-link>

          <!-- Admin Login Card -->
          <router-link to="/admins/login" class="block">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/30 p-8 hover:bg-white/90 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-3xl">
              <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-r from-slate-800 to-gray-700 rounded-2xl flex items-center justify-center">
                  <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                  </svg>
                </div>
                <div class="flex-1">
                  <h3 class="text-xl font-semibold text-gray-900">Sign in as Admin</h3>
                  <p class="text-gray-600">Access administrative panel</p>
                </div>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </div>
            </div>
          </router-link>
        </div>

        <!-- Footer -->
        <div class="text-center">
          <p class="text-sm text-gray-600">
            © {{ new Date().getFullYear() }} Laravel Vue Blog. All rights reserved.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Welcome',
  setup() {
    console.log('Welcome component loaded!');
    return {}
  }
}
</script>

<style scoped>
.bg-grid-pattern {
  background-image:
    linear-gradient(rgba(0,0,0,0.1) 1px, transparent 1px),
    linear-gradient(90deg, rgba(0,0,0,0.1) 1px, transparent 1px);
  background-size: 20px 20px;
}
</style>
