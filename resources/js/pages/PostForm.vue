<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <router-link to="/" class="text-xl font-bold text-gray-900">
              Laravel Vue Blog
            </router-link>
          </div>
          <div class="flex items-center space-x-4">
            <router-link to="/" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
              Home
            </router-link>
            <router-link to="/posts" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
              Posts
            </router-link>
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
              <h2 class="text-2xl font-bold text-gray-900">
                {{ isEditing ? 'Edit Post' : 'Create New Post' }}
              </h2>
              <router-link to="/posts" class="text-blue-500 hover:text-blue-700 font-medium">
                ← Back to Posts
              </router-link>
            </div>

            <form @submit.prevent="submitForm" enctype="multipart/form-data" class="space-y-6">
              <!-- Title Field -->
              <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
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

              <!-- Content Field -->
              <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
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
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
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
                    @click="removeNewImage"
                    class="px-3 py-2 text-sm text-red-600 hover:text-red-800 font-medium border border-red-300 hover:bg-red-50 rounded"
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
                <div v-else-if="post && post.image && !hideCurrentImage" class="mt-3">
                  <div class="flex items-center space-x-3">
                    <img
                      :src="post.image_url || `/storage/${post.image}`"
                      alt="Current image"
                      class="w-32 h-32 object-cover rounded-lg border border-gray-300"
                    />
                    <div>
                      <p class="text-sm text-gray-500 mb-2">Current image</p>
                      <button
                        type="button"
                        @click="removeImage"
                        class="px-3 py-1 text-sm text-red-600 hover:text-red-800 font-medium border border-red-300 hover:bg-red-50 rounded"
                      >
                        Remove Current Image
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Status Field -->
              <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
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
                  <span v-if="form.status === 'published'" class="text-green-600">
                    Published posts are visible to everyone.
                  </span>
                  <span v-else-if="form.status === 'draft'" class="text-yellow-600">
                    Draft posts are only visible to you.
                  </span>
                </div>
              </div>

              <!-- General Error -->
              <div v-if="generalError" class="bg-red-50 border border-red-200 rounded-md p-4">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                  <div class="ml-3">
                    <p class="text-sm text-red-700 font-medium">
                      {{ generalError }}
                    </p>
                  </div>
                </div>
              </div>

              <!-- Submit Button -->
              <div class="flex justify-end space-x-4">
                <router-link
                  to="/posts"
                  class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                  Cancel
                </router-link>
                <button
                  type="submit"
                  :disabled="loading"
                  class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <span v-if="loading" class="flex items-center">
                    <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ isEditing ? 'Updating...' : 'Creating...' }}
                  </span>
                  <span v-else>{{ isEditing ? 'Update Post' : 'Create Post' }}</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'

export default {
  name: 'PostForm',
  setup() {
    const router = useRouter()
    const route = useRoute()

    const loading = ref(false)
    const generalError = ref('')
    const errors = ref({})
    const imageInput = ref(null)
    const imagePreview = ref(null)
    const post = ref(null)

    const form = ref({
      title: '',
      content: '',
      status: '',
      image: null,
      current_image: null,
      remove_image: false,
    })

    // Add a ref to track if current image should be hidden
    const hideCurrentImage = ref(false)

    const isEditing = computed(() => {
      return route.params.id !== undefined
    })

    const fetchPost = async () => {
      if (!isEditing.value) return

      try {
        const response = await axios.get(`/api/posts/${route.params.id}`)
        post.value = response.data.post

        errors.value = {}
        generalError.value = ""

        form.value = {
          title: post.value.title,
          content: post.value.content,
          status: post.value.status,
          image: null,
          current_image: post.value.image_url || null,
          remove_image: false,
        }
        // Reset hide current image flag
        hideCurrentImage.value = false
      } catch (error) {
        console.log("Error fetching post:", error)
        generalError.value = "Error loading post. Please try again."
      }
    }

    const handleImageChange = (event) => {
      const file = event.target.files[0]
      if (file) {
        form.value.image = file
        const reader = new FileReader()
        reader.onload = (e) => {
          imagePreview.value = e.target.result
        }
        reader.readAsDataURL(file)
      }
    }

    const removeImage = () => {
      if (confirm('Are you sure you want to remove the current image?')) {
        form.value.image = null
        form.value.current_image = null
        form.value.remove_image = true
        imagePreview.value = null
        hideCurrentImage.value = true // Hide the current image from DOM
        if (imageInput.value) {
          imageInput.value.value = ''
        }
      }
    }

    const removeNewImage = () => {
      if (confirm('Are you sure you want to remove the selected image?')) {
        form.value.image = null
        imagePreview.value = null
        if (imageInput.value) {
          imageInput.value.value = ''
        }
      }
    }

    const submitForm = async () => {
      loading.value = true
      generalError.value = ""
      errors.value = {}

      try {
        const formData = new FormData()
        formData.append('title', form.value.title)
        formData.append('content', form.value.content)
        formData.append('status', form.value.status)

        if (form.value.image) {
          formData.append('image', form.value.image)
        }

        if (form.value.remove_image) {
          formData.append('remove_image', '1')
        }

        if (isEditing.value) {
          formData.append('_method', 'PUT')
        }

        const url = isEditing.value ? `/api/posts/${route.params.id}` : "/api/posts"
        const method = 'post'

        await axios({
          method: method,
          url: url,
          data: formData,
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })

        router.push("/posts")
      } catch (error) {
        if (error.response?.status === 401) {
          // Authentication error - redirect to login
          generalError.value = "Please login first to create/edit posts."
          setTimeout(() => {
            router.push('/')
          }, 2000)
        } else if (error.response?.data?.errors) {
          errors.value = error.response.data.errors
        } else if (error.response?.data?.message) {
          generalError.value = error.response.data.message
        } else {
          generalError.value = "An error occurred. Please try again."
        }
      } finally {
        loading.value = false
      }
    }

    onMounted(() => {
      fetchPost()
    })

    return {
      form,
      post,
      loading,
      errors,
      generalError,
      isEditing,
      submitForm,
      imageInput,
      imagePreview,
      hideCurrentImage,
      handleImageChange,
      removeImage,
      removeNewImage,
    }
  }
}
</script>
