<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { LinkIcon, TagIcon, HeartIcon } from '@heroicons/vue/24/outline';

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
}

defineProps<Props>();

const form = useForm({
  title: '',
  url: '',
  description: '',
  category_id: '',
  link_type_id: '',
  is_favorite: false,
  tags: '',
});

const submit = () => {
  form.post(route('links.store'));
};
</script>

<template>
    <Head title="Add New Link" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Add New Link
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-xl">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- URL Field -->
                            <div>
                                <label for="url" class="block text-sm font-medium text-gray-700 mb-2">
                                    <LinkIcon class="h-4 w-4 inline mr-1" />
                                    URL
                                </label>
                                <TextInput
                                    id="url"
                                    v-model="form.url"
                                    type="url"
                                    class="mt-1 block w-full"
                                    placeholder="https://example.com"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.url" />
                            </div>

                            <!-- Title Field -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Title
                                </label>
                                <TextInput
                                    id="title"
                                    v-model="form.title"
                                    type="text"
                                    class="mt-1 block w-full"
                                    placeholder="Link title (will be auto-filled if left empty)"
                                />
                                <InputError class="mt-2" :message="form.errors.title" />
                            </div>

                            <!-- Description Field -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    rows="3"
                                    placeholder="Optional description..."
                                ></textarea>
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>

                            <!-- Category and Link Type -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Category
                                    </label>
                                    <select
                                        id="category_id"
                                        v-model="form.category_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    >
                                        <option value="">Select a category...</option>
                                        <option 
                                            v-for="category in categories" 
                                            :key="category.id" 
                                            :value="category.id"
                                        >
                                            {{ category.name }}
                                        </option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.category_id" />
                                </div>

                                <div>
                                    <label for="link_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Link Type
                                    </label>
                                    <select
                                        id="link_type_id"
                                        v-model="form.link_type_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    >
                                        <option value="">Select a type...</option>
                                        <option 
                                            v-for="linkType in link_types" 
                                            :key="linkType.id" 
                                            :value="linkType.id"
                                        >
                                            {{ linkType.name }}
                                        </option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.link_type_id" />
                                </div>
                            </div>

                            <!-- Tags Field -->
                            <div>
                                <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                                    <TagIcon class="h-4 w-4 inline mr-1" />
                                    Tags
                                </label>
                                <TextInput
                                    id="tags"
                                    v-model="form.tags"
                                    type="text"
                                    class="mt-1 block w-full"
                                    placeholder="tag1, tag2, tag3"
                                />
                                <p class="mt-1 text-sm text-gray-500">
                                    Separate multiple tags with commas
                                </p>
                                <InputError class="mt-2" :message="form.errors.tags" />
                            </div>

                            <!-- Favorite Checkbox -->
                            <div class="flex items-center">
                                <input
                                    id="is_favorite"
                                    v-model="form.is_favorite"
                                    type="checkbox"
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                                >
                                <label for="is_favorite" class="ml-2 text-sm text-gray-700 flex items-center">
                                    <HeartIcon class="h-4 w-4 mr-1 text-red-500" />
                                    Mark as favorite
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-center justify-end space-x-4">
                                <a 
                                    :href="route('links.index')"
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-medium transition-colors"
                                >
                                    Cancel
                                </a>
                                <PrimaryButton 
                                    :class="{ 'opacity-25': form.processing }" 
                                    :disabled="form.processing"
                                >
                                    <LinkIcon class="h-4 w-4 mr-2" />
                                    Add Link
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
