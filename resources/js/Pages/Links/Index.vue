<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { PlusIcon, FunnelIcon } from '@heroicons/vue/24/outline';

interface Category {
  id: number;
  name: string;
  color: string;
  icon: string;
}

interface LinkType {
  id: number;
  name: string;
  icon: string;
  description?: string;
}

interface Props {
  categories: Category[];
  link_types: LinkType[];
  filters: {
    category_id?: number;
    link_type_id?: number;
    is_favorite?: boolean;
    search?: string;
  };
}

defineProps<Props>();
</script>

<template>
    <Head title="Links" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Links
                </h2>
                <Link
                    :href="route('links.create')"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center"
                >
                    <PlusIcon class="h-4 w-4 mr-2" />
                    Add Link
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-xl">
                    <div class="p-6">
                        <!-- Filters -->
                        <div class="mb-6 flex flex-wrap gap-4 items-center">
                            <div class="flex items-center space-x-2">
                                <FunnelIcon class="h-5 w-5 text-gray-400" />
                                <span class="text-sm font-medium text-gray-700">Filters:</span>
                            </div>
                            
                            <select class="rounded-md border-gray-300 text-sm">
                                <option value="">All Categories</option>
                                <option 
                                    v-for="category in categories" 
                                    :key="category.id" 
                                    :value="category.id"
                                    :selected="filters.category_id === category.id"
                                >
                                    {{ category.name }}
                                </option>
                            </select>

                            <select class="rounded-md border-gray-300 text-sm">
                                <option value="">All Types</option>
                                <option 
                                    v-for="linkType in link_types" 
                                    :key="linkType.id" 
                                    :value="linkType.id"
                                    :selected="filters.link_type_id === linkType.id"
                                >
                                    {{ linkType.name }}
                                </option>
                            </select>

                            <div class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    id="favorites-only"
                                    :checked="filters.is_favorite"
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                                >
                                <label for="favorites-only" class="ml-2 text-sm text-gray-700">
                                    Favorites only
                                </label>
                            </div>

                            <input 
                                type="text" 
                                placeholder="Search links..." 
                                :value="filters.search"
                                class="rounded-md border-gray-300 text-sm flex-1 min-w-64"
                            >
                        </div>

                        <!-- Links will be loaded via API -->
                        <div id="links-container" class="space-y-4">
                            <!-- This will be populated by Vue.js components -->
                            <div class="text-center py-12">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M34 20v-4a8 8 0 00-16 0v4m16 0H14m20 0v20a2 2 0 01-2 2H16a2 2 0 01-2-2V20m20 0H14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Loading links...</h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Links will appear here once loaded from the API.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
