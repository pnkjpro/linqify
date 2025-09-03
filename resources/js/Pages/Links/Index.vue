<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { PlusIcon, FunnelIcon, HeartIcon, EyeIcon, PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';
import { HeartIcon as HeartIconSolid } from '@heroicons/vue/24/solid';
import { ref } from 'vue';

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

interface LinkItem {
  id: number;
  title: string;
  url: string;
  description?: string;
  is_favorite: boolean;
  click_count: number;
  category?: Category;
  link_type?: LinkType;
  tags?: string;
  created_at: string;
}

interface Props {
  categories: Category[];
  link_types: LinkType[];
  links: LinkItem[];
  filters: {
    category_id?: number;
    link_type_id?: number;
    is_favorite?: boolean;
    search?: string;
  };
}

const props = defineProps<Props>();
const links = ref<LinkItem[]>(props.links);

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

const deleteLink = (linkId: number) => {
  if (confirm('Are you sure you want to delete this link?')) {
    router.delete(route('links.destroy', linkId), {
      onSuccess: () => {
        // Remove the link from the local array
        links.value = links.value.filter(link => link.id !== linkId);
      }
    });
  }
};

const toggleFavorite = async (linkId: number) => {
  try {
    const response = await fetch(`/api/links/${linkId}/favorite`, {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      }
    });
    
    if (response.ok) {
      // Update the local state
      const link = links.value.find(l => l.id === linkId);
      if (link) {
        link.is_favorite = !link.is_favorite;
      }
    }
  } catch (error) {
    console.error('Error toggling favorite:', error);
  }
};
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

                        <!-- Links List -->
                        <div v-if="links.length === 0" class="text-center py-12">
                            <div class="text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M34 20v-4a8 8 0 00-16 0v4m16 0H14m20 0v20a2 2 0 01-2 2H16a2 2 0 01-2-2V20m20 0H14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No links found</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Get started by creating your first link.
                                </p>
                                <div class="mt-6">
                                    <Link
                                        :href="route('links.create')"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700"
                                    >
                                        <PlusIcon class="h-4 w-4 mr-2" />
                                        Add Your First Link
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <div v-else class="space-y-4">
                            <div 
                                v-for="link in links" 
                                :key="link.id"
                                class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow"
                            >
                                <div class="flex items-start justify-between">
                                    <div class="flex-1 min-w-0">
                                        <!-- Link Title and URL -->
                                        <div class="flex items-center mb-2">
                                            <a 
                                                :href="link.url" 
                                                target="_blank" 
                                                class="text-lg font-medium text-blue-600 hover:text-blue-500 truncate mr-2"
                                            >
                                                {{ link.title }}
                                            </a>
                                            <button 
                                                @click="toggleFavorite(link.id)"
                                                class="text-red-500 hover:text-red-600"
                                            >
                                                <HeartIconSolid v-if="link.is_favorite" class="h-5 w-5" />
                                                <HeartIcon v-else class="h-5 w-5" />
                                            </button>
                                        </div>
                                        
                                        <!-- Description -->
                                        <p v-if="link.description" class="text-sm text-gray-600 mb-3">
                                            {{ link.description }}
                                        </p>
                                        
                                        <!-- URL -->
                                        <p class="text-xs text-gray-500 mb-3 truncate">
                                            {{ link.url }}
                                        </p>
                                        
                                        <!-- Metadata -->
                                        <div class="flex items-center space-x-4 text-xs text-gray-500">
                                            <div class="flex items-center">
                                                <EyeIcon class="h-4 w-4 mr-1" />
                                                {{ link.click_count || 0 }} views
                                            </div>
                                            
                                            <div v-if="link.category" class="flex items-center">
                                                <span class="mr-1">{{ link.category.icon }}</span>
                                                <span class="px-2 py-1 rounded-full text-xs" :class="getColorClasses(link.category.color)">
                                                    {{ link.category.name }}
                                                </span>
                                            </div>
                                            
                                            <div v-if="link.link_type">
                                                {{ link.link_type.name }}
                                            </div>
                                            
                                            <div>
                                                {{ new Date(link.created_at).toLocaleDateString() }}
                                            </div>
                                        </div>
                                        
                                        <!-- Tags -->
                                        <div v-if="link.tags" class="mt-3">
                                            <div class="flex flex-wrap gap-1">
                                                <span 
                                                    v-for="tag in link.tags.split(',')" 
                                                    :key="tag.trim()"
                                                    class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs"
                                                >
                                                    #{{ tag.trim() }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Actions -->
                                    <div class="flex items-center space-x-2 ml-4">
                                        <Link
                                            :href="route('links.edit', link.id)"
                                            class="p-2 text-gray-400 hover:text-gray-600 rounded-md hover:bg-gray-100"
                                            title="Edit link"
                                        >
                                            <PencilIcon class="h-4 w-4" />
                                        </Link>
                                        
                                        <button
                                            @click="deleteLink(link.id)"
                                            class="p-2 text-gray-400 hover:text-red-600 rounded-md hover:bg-gray-100"
                                            title="Delete link"
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
