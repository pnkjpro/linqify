<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { PlusIcon, PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';

interface Category {
  id: number;
  name: string;
  description?: string;
  color: string;
  icon: string;
  links_count: number;
}

interface Props {
  categories: Category[];
}

defineProps<Props>();

const getColorClasses = (color: string) => {
  const colorMap: Record<string, string> = {
    'blue': 'bg-blue-100 text-blue-800 border-blue-200',
    'green': 'bg-green-100 text-green-800 border-green-200',
    'red': 'bg-red-100 text-red-800 border-red-200',
    'yellow': 'bg-yellow-100 text-yellow-800 border-yellow-200',
    'purple': 'bg-purple-100 text-purple-800 border-purple-200',
    'pink': 'bg-pink-100 text-pink-800 border-pink-200',
    'indigo': 'bg-indigo-100 text-indigo-800 border-indigo-200',
    'gray': 'bg-gray-100 text-gray-800 border-gray-200',
  };
  return colorMap[color] || colorMap.gray;
};
</script>

<template>
    <Head title="Categories" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Categories
                </h2>
                <Link
                    :href="route('categories.create')"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center"
                >
                    <PlusIcon class="h-4 w-4 mr-2" />
                    Add Category
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-xl">
                    <div class="p-6">
                        <div v-if="categories.length === 0" class="text-center py-12">
                            <div class="text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M7 21h34M7 15h34M7 27h34M7 33h34" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No categories</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Get started by creating your first category.
                                </p>
                                <div class="mt-6">
                                    <Link
                                        :href="route('categories.create')"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700"
                                    >
                                        <PlusIcon class="h-4 w-4 mr-2" />
                                        Add Category
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div 
                                v-for="category in categories" 
                                :key="category.id"
                                class="border rounded-lg p-6 hover:shadow-md transition-shadow"
                                :class="getColorClasses(category.color)"
                            >
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <span class="text-2xl mr-3">{{ category.icon }}</span>
                                            <h3 class="text-lg font-semibold">{{ category.name }}</h3>
                                        </div>
                                        
                                        <p v-if="category.description" class="text-sm opacity-75 mb-3">
                                            {{ category.description }}
                                        </p>
                                        
                                        <div class="flex items-center text-sm opacity-75">
                                            <span>{{ category.links_count }} link{{ category.links_count !== 1 ? 's' : '' }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex space-x-2 ml-4">
                                        <Link
                                            :href="route('categories.edit', category.id)"
                                            class="p-2 hover:bg-white hover:bg-opacity-20 rounded-md transition-colors"
                                            title="Edit category"
                                        >
                                            <PencilIcon class="h-4 w-4" />
                                        </Link>
                                        
                                        <button
                                            class="p-2 hover:bg-white hover:bg-opacity-20 rounded-md transition-colors text-red-600"
                                            title="Delete category"
                                            @click="deleteCategory(category.id)"
                                        >
                                            <TrashIcon class="h-4 w-4" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script lang="ts">
import { router } from '@inertiajs/vue3';

export default {
    methods: {
        deleteCategory(categoryId: number) {
            if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
                router.delete(route('categories.destroy', categoryId));
            }
        }
    }
};
</script>
