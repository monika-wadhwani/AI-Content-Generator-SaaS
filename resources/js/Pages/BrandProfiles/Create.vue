<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    brand_name: '',
    industry: '',
    target_audience: '',
    tone: '',
    description: '',
    words_to_avoid: '',
});

const submit = () => {
    form.post(route('brand-profiles.store'));
};

</script>

<template>
    <AuthenticatedLayout>
        <Head title="Create Brand Profile" />

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h2 class="text-lg font-semibold mb-6">Set Up Your Brand</h2>

                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Brand Name</label>
                            <input v-model="form.brand_name" type="text" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                            <div v-if="form.errors.brand_name" class="text-red-600 text-sm mt-1">
                                {{ form.errors.brand_name }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Industry</label>
                            <input v-model="form.industry" type="text" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Target Audience</label>
                            <input v-model="form.target_audience" type="text" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tone</label>
                            <select v-model="form.tone" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Select tone</option>
                                <option value="professional">Professional</option>
                                <option value="friendly">Friendly</option>
                                <option value="witty">Witty</option>
                                <option value="formal">Formal</option>
                                <option value="casual">Casual</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Brand Description</label>
                            <textarea v-model="form.description" rows="4"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Words/Phrases to Avoid (optional)</label>
                            <input v-model="form.words_to_avoid" type="text" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                        </div>

                        <button type="submit" :disabled="form.processing"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                            Save Brand Profile
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>