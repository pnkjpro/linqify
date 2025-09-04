<template>
  <div class="w-full max-w-2xl mx-auto space-y-6">
    <div class="bg-white rounded-lg shadow-md border border-gray-200">
      <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900">URL Shortener</h2>
        <p class="text-gray-600 mt-1">
          Create short, shareable links instantly. No registration required!
        </p>
      </div>
      <div class="p-6">
        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div class="space-y-2">
            <label for="url" class="block text-sm font-medium text-gray-700">
              URL to shorten
            </label>
            <input
              id="url"
              v-model="form.url"
              type="url"
              placeholder="https://example.com/very-long-url"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required
            />
          </div>

          <div class="space-y-2">
            <label for="customStub" class="block text-sm font-medium text-gray-700">
              Custom short URL (optional)
            </label>
            <div class="flex items-center space-x-2">
              <span class="text-sm text-gray-500 whitespace-nowrap">
                {{ baseUrl }}/
              </span>
              <input
                id="customStub"
                v-model="form.customStub"
                type="text"
                placeholder="my-custom-link"
                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                pattern="[a-zA-Z0-9_-]+"
                minlength="3"
                maxlength="20"
                @input="checkStubDebounced"
              />
              <div v-if="isCheckingStub" class="w-4 h-4">
                <svg class="animate-spin h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </div>
            </div>
            <div v-if="form.customStub && stubAvailable !== null" class="text-sm">
              <span :class="stubAvailable ? 'text-green-600' : 'text-red-600'">
                {{ stubAvailable ? '✓ Available' : '✗ Not available' }}
              </span>
            </div>
            <p class="text-xs text-gray-500">
              Only letters, numbers, hyphens, and underscores allowed (3-20 characters)
            </p>
          </div>

          <button
            type="submit"
            :disabled="isLoading || !isValidUrl(form.url) || (form.customStub && stubAvailable === false)"
            class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="isLoading" class="flex items-center justify-center">
              <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Shortening...
            </span>
            <span v-else>Shorten URL</span>
          </button>
        </form>

        <div v-if="error" class="mt-4 p-4 bg-red-50 border border-red-200 rounded-md">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm text-red-800">{{ error }}</p>
            </div>
          </div>
        </div>

        <div v-if="result" class="mt-6 bg-white rounded-lg shadow-md border border-gray-200">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-green-600">Success!</h3>
            <p class="text-gray-600">Your URL has been shortened</p>
          </div>
          <div class="p-6 space-y-4">
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-700">Short URL</label>
              <div class="flex items-center space-x-2">
                <input
                  :value="result.full_short_url"
                  readonly
                  class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50 focus:outline-none"
                />
                <button
                  type="button"
                  @click="copyToClipboard(result.full_short_url)"
                  class="px-3 py-2 border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <svg v-if="copied" class="h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                  <svg v-else class="h-4 w-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
                    <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" />
                  </svg>
                </button>
                <button
                  type="button"
                  @click="openUrl(result.full_short_url)"
                  class="px-3 py-2 border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                  </svg>
                </button>
              </div>
            </div>

            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-700">Original URL</label>
              <input
                :value="result.original_url"
                readonly
                class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-sm focus:outline-none"
              />
            </div>

            <div class="flex justify-between text-sm text-gray-500">
              <span>Clicks: {{ result.click_count }}</span>
              <span>Created: {{ formatDate(result.created_at) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import axios from 'axios'

interface ShortenedUrl {
  id: number
  original_url: string
  short_url: string
  full_short_url: string
  title: string
  click_count: number
  created_at: string
}

interface ApiResponse {
  success: boolean
  data?: ShortenedUrl
  message?: string
}

const form = ref({
  url: '',
  customStub: ''
})

const isLoading = ref(false)
const isCheckingStub = ref(false)
const result = ref<ShortenedUrl | null>(null)
const error = ref('')
const copied = ref(false)
const stubAvailable = ref<boolean | null>(null)
let debounceTimer: number | null = null

const baseUrl = computed(() => window.location.origin)

const isValidUrl = (url: string): boolean => {
  try {
    new URL(url)
    return true
  } catch {
    return false
  }
}

const checkStubAvailability = async (stub: string) => {
  if (!stub || stub.length < 3) {
    stubAvailable.value = null
    return
  }

  isCheckingStub.value = true
  try {
    const response = await axios.post('/api/shorten/check-stub', { stub })
    stubAvailable.value = response.data.available
  } catch (error) {
    console.error('Error checking stub:', error)
    stubAvailable.value = null
  } finally {
    isCheckingStub.value = false
  }
}

const checkStubDebounced = () => {
  if (debounceTimer) {
    clearTimeout(debounceTimer)
  }
  debounceTimer = setTimeout(() => {
    checkStubAvailability(form.value.customStub)
  }, 500)
}

const handleSubmit = async () => {
  if (!form.value.url) return

  isLoading.value = true
  error.value = ''
  result.value = null

  try {
    const response = await axios.post<ApiResponse>('/api/shorten', {
      url: form.value.url,
      custom_stub: form.value.customStub || undefined
    })

    if (response.data.success && response.data.data) {
      result.value = response.data.data
      form.value.url = ''
      form.value.customStub = ''
      stubAvailable.value = null
    } else {
      error.value = response.data.message || 'An error occurred while shortening the URL'
    }
  } catch (err: any) {
    console.error('Error shortening URL:', err)
    if (err.response?.data?.message) {
      error.value = err.response.data.message
    } else {
      error.value = 'Network error occurred. Please try again.'
    }
  } finally {
    isLoading.value = false
  }
}

const copyToClipboard = async (text: string) => {
  try {
    await navigator.clipboard.writeText(text)
    copied.value = true
    setTimeout(() => {
      copied.value = false
    }, 2000)
  } catch (error) {
    console.error('Failed to copy:', error)
    // Fallback for older browsers
    const textArea = document.createElement('textarea')
    textArea.value = text
    document.body.appendChild(textArea)
    textArea.select()
    document.execCommand('copy')
    document.body.removeChild(textArea)
    copied.value = true
    setTimeout(() => {
      copied.value = false
    }, 2000)
  }
}

const openUrl = (url: string) => {
  window.open(url, '_blank')
}

const formatDate = (dateString: string): string => {
  return new Date(dateString).toLocaleDateString()
}
</script>
