<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    brandProfiles: Array,
});
</script>

<template>

    <AuthenticatedLayout>
        <Head title="Brand Profiles" />

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold">Your Brand Profiles</h2>
                    <Link :href="route('brand-profiles.create')" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        + New Brand Profile
                    </Link>
                </div>
                <div v-if="brandProfiles.length === 0" class="bg-white p-6 rounded-lg shadow text-gray-500">
                No brand profiles yet. Create one to get started.
                </div>

                <div v-else class="space-y-4">
                    <div v-for="profile in brandProfiles" :key="profile.id" class="bg-white p-6 rounded-lg shadow">
                        <h3 class="font-semibold text-lg">{{ profile.brand_name }}</h3>
                        <p class="text-gray-600">{{ profile.industry }} • {{ profile.tone }} tone</p>
                        <p class="text-gray-500 text-sm mt-2">{{ profile.description }}</p>

                        <Link :href="route('content-generations.create', profile.id)" class="inline-block mt-4 text-indigo-600 hover:underline">
                            Generate Content →
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>