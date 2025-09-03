<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { 
  ChartBarIcon, 
  HeartIcon, 
  GlobeAltIcon, 
  FolderIcon,
  EyeIcon,
  StarIcon,
  ClockIcon,
  FireIcon
} from '@heroicons/vue/24/outline';

interface Statistics {
  total_links: number;
  total_categories: number;
  favorite_links: number;
  public_links: number;
}

interface LinkItem {
  id: number;
  title: string;
  url: string;
  description?: string;
  category: {
    name: string;
    color: string;
  };
  linkType: {
    name: string;
    icon: string;
  };
  click_count: number;
  is_favorite: boolean;
  created_at: string;
}

interface CategoryData {
  name: string;
  count: number;
  color: string;
}

interface Props {
  statistics: Statistics;
  recent_links: LinkItem[];
  favorite_links: LinkItem[];
  popular_links: LinkItem[];
  links_by_category: CategoryData[];
  recent_activity: Array<{ date: string; count: number }>;
  link_type_distribution: Array<{ name: string; icon: string; count: number }>;
}

defineProps<Props>();

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
  });
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Dashboard
                </h2>
                <Link
                    :href="route('links.create')"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                >
                    Add New Link
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <ChartBarIcon class="h-8 w-8 text-blue-600" />
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Links</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ statistics.total_links }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <FolderIcon class="h-8 w-8 text-green-600" />
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Categories</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ statistics.total_categories }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <HeartIcon class="h-8 w-8 text-red-600" />
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Favorites</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ statistics.favorite_links }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <GlobeAltIcon class="h-8 w-8 text-purple-600" />
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Public Links</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ statistics.public_links }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Links Section -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <ClockIcon class="h-5 w-5 mr-2 text-gray-600" />
                                Recent Links
                            </h3>
                            <Link 
                                :href="route('links.index')" 
                                class="text-sm text-blue-600 hover:text-blue-800"
                            >
                                View all
                            </Link>
                        </div>
                    </div>
                    <div class="p-6">
                        <div v-if="recent_links.length > 0" class="space-y-4">
                            <div 
                                v-for="link in recent_links" 
                                :key="link.id"
                                class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors"
                            >
                                <div class="flex-shrink-0">
                                    <div 
                                        class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-sm font-medium"
                                        :style="{ backgroundColor: link.category.color }"
                                    >
                                        {{ link.category.name.charAt(0) }}
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ link.title }}
                                        </p>
                                        <div class="flex items-center space-x-1">
                                            <HeartIcon 
                                                v-if="link.is_favorite" 
                                                class="h-4 w-4 text-red-500 fill-current"
                                            />
                                            <span class="text-xs text-gray-500 flex items-center">
                                                <EyeIcon class="h-3 w-3 mr-1" />
                                                {{ link.click_count }}
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 truncate">{{ link.description }}</p>
                                    <div class="flex items-center justify-between mt-1">
                                        <span 
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium"
                                            :style="{ 
                                                backgroundColor: link.category.color + '20', 
                                                color: link.category.color 
                                            }"
                                        >
                                            {{ link.category.name }}
                                        </span>
                                        <span class="text-xs text-gray-400">
                                            {{ formatDate(link.created_at) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center py-8">
                            <ClockIcon class="mx-auto h-12 w-12 text-gray-400" />
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No recent links</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by adding a new link.</p>
                            <div class="mt-6">
                                <Link
                                    :href="route('links.create')"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                                >
                                    Add your first link
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
