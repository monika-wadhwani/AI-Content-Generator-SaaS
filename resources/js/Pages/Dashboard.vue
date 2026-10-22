<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Dashboard" />

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">
                        Welcome back, {{ $page.props.auth.user.name }}
                    </h2>
                    <p class="text-gray-500 mb-6">
                        Manage your brand profiles and generate AI-powered content.
                    </p>

                    <Link :href="route('brand-profiles.index')" 
                        class="inline-block bg-indigo-600 text-white px-5 py-2.5 rounded-lg font-medium hover:bg-indigo-700">
                        View Brand Profiles →
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>