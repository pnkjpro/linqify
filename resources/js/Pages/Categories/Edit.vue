<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { TagIcon } from '@heroicons/vue/24/outline';

interface Category {
  id: number;
  name: string;
  description?: string;
  color: string;
  icon: string;
}

interface Props {
  category: Category;
}

const props = defineProps<Props>();

const form = useForm({
  name: props.category.name,
  description: props.category.description || '',
  color: props.category.color,
  icon: props.category.icon,
});

const submit = () => {
  form.put(route('categories.update', props.category.id));
};

const colors = [
  { name: 'Blue', value: 'blue', class: 'bg-blue-500' },
  { name: 'Green', value: 'green', class: 'bg-green-500' },
  { name: 'Red', value: 'red', class: 'bg-red-500' },
  { name: 'Yellow', value: 'yellow', class: 'bg-yellow-500' },
  { name: 'Purple', value: 'purple', class: 'bg-purple-500' },
  { name: 'Pink', value: 'pink', class: 'bg-pink-500' },
  { name: 'Indigo', value: 'indigo', class: 'bg-indigo-500' },
  { name: 'Gray', value: 'gray', class: 'bg-gray-500' },
];

const commonIcons = ['📁', '💼', '🎨', '📚', '🔧', '🌟', '🚀', '💡', '📱', '💻', '🎮', '🎵', '📸', '🏠', '🍕', '✈️'];
</script>

<template>
    <Head title="Edit Category" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Category
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-xl">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Category Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Category Name
                                </label>
                                <TextInput
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    placeholder="e.g., Work, Personal, Bookmarks"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Description (Optional)
                                </label>
                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    rows="3"
                                    placeholder="Brief description of this category..."
                                ></textarea>
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>

                            <!-- Color Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Color
                                </label>
                                <div class="grid grid-cols-4 gap-3">
                                    <label 
                                        v-for="color in colors" 
                                        :key="color.value"
                                        class="flex items-center cursor-pointer"
                                    >
                                        <input
                                            type="radio"
                                            v-model="form.color"
                                            :value="color.value"
                                            class="sr-only"
                                        />
                                        <div 
                                            class="w-8 h-8 rounded-full border-2 transition-all"
                                            :class="[
                                                color.class,
                                                form.color === color.value 
                                                    ? 'border-gray-800 scale-110' 
                                                    : 'border-gray-300'
                                            ]"
                                        ></div>
                                        <span class="ml-2 text-sm">{{ color.name }}</span>
                                    </label>
                                </div>
                                <InputError class="mt-2" :message="form.errors.color" />
                            </div>

                            <!-- Icon Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Icon
                                </label>
                                <div class="grid grid-cols-8 gap-2 mb-4">
                                    <button
                                        v-for="icon in commonIcons"
                                        :key="icon"
                                        type="button"
                                        @click="form.icon = icon"
                                        class="p-2 text-2xl border rounded-md hover:bg-gray-50 transition-colors"
                                        :class="form.icon === icon ? 'border-blue-500 bg-blue-50' : 'border-gray-300'"
                                    >
                                        {{ icon }}
                                    </button>
                                </div>
                                <TextInput
                                    v-model="form.icon"
                                    type="text"
                                    class="mt-2 block w-full"
                                    placeholder="Or enter custom emoji/icon"
                                />
                                <InputError class="mt-2" :message="form.errors.icon" />
                            </div>

                            <!-- Preview -->
                            <div class="border rounded-lg p-4 bg-gray-50">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Preview</h4>
                                <div class="flex items-center">
                                    <span class="text-2xl mr-3">{{ form.icon || '📁' }}</span>
                                    <div>
                                        <div class="font-medium">{{ form.name || 'Category Name' }}</div>
                                        <div v-if="form.description" class="text-sm text-gray-600">
                                            {{ form.description }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-center justify-end space-x-4">
                                <a 
                                    :href="route('categories.index')"
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-medium transition-colors"
                                >
                                    Cancel
                                </a>
                                <PrimaryButton 
                                    :class="{ 'opacity-25': form.processing }" 
                                    :disabled="form.processing"
                                >
                                    <TagIcon class="h-4 w-4 mr-2" />
                                    Update Category
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
