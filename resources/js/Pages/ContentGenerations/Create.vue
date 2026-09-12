<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onUnmounted } from 'vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    brandProfile: Object,
    generations: Array,
});

const form = useForm({
    topic: '',
});

const localGenerations = ref([...props.generations]);
let pollingInterval = null;

const submit = () => {
    form.post(route('content-generations.store', props.brandProfile.id), {
        onSuccess: () => {
            form.reset();
            // Naya pending generation turant list mein dikhane ke liye add karo
            startPolling();
        },
    });
};

const startPolling = () => {
    // Har 2 second mein check karo koi pending generation complete hui ya nahi
    pollingInterval = setInterval(async () => {
        const pendingOnes = localGenerations.value.filter(g => g.status === 'pending');

        if (pendingOnes.length === 0) {
            clearInterval(pollingInterval);
            return;
        }

        for (const gen of pendingOnes) {
            const response = await axios.get(route('content-generations.check', gen.id));
            
            if (response.data.status !== 'pending') {
                const index = localGenerations.value.findIndex(g => g.id === gen.id);
                localGenerations.value[index].status = response.data.status;
                localGenerations.value[index].generated_content = response.data.generated_content;
            }
        }
    }, 2000);
};

// Page load hote hi, agar already koi pending generation hai, polling start karo
if (localGenerations.value.some(g => g.status === 'pending')) {
    startPolling();
}

onUnmounted(() => {
    if (pollingInterval) clearInterval(pollingInterval);
});

const retryGeneration = (generationId) => {
    console.log(generationId);
    router.post(route('content-generations.retry', generationId), {}, {
        onSuccess: () => {
            const index = localGenerations.value.findIndex(g => g.id === generationId);
            localGenerations.value[index].status = 'pending';
            startPolling();
        }
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`Generate Content — ${brandProfile.brand_name}`" />

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">{{ brandProfile.brand_name }}</h2>
                    <p class="text-gray-500 mt-1">
                        <span class="capitalize">{{ brandProfile.tone }}</span> tone • {{ brandProfile.industry }}
                    </p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        What should we write about?
                    </label>
                    <form @submit.prevent="submit" class="flex gap-3">
                        <input v-model="form.topic" type="text" 
                            placeholder="e.g. New product launch, seasonal sale..."
                            class="flex-1 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                        <button type="submit" :disabled="form.processing"
                            class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-indigo-700 disabled:opacity-50 transition flex items-center gap-2">
                            <svg v-if="form.processing" class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            {{ form.processing ? 'Starting...' : 'Generate' }}
                        </button>
                    </form>
                </div>

                <div v-if="localGenerations.length === 0" 
                    class="bg-white p-10 rounded-xl shadow-sm border border-gray-100 text-center">
                    <p class="text-gray-400">No content generated yet. Try writing about something above.</p>
                </div>

                <div v-else class="space-y-4">
                    <div v-for="gen in localGenerations" :key="gen.id" 
                        class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="font-semibold text-gray-900">{{ gen.topic }}</h3>
                            
                            <div class="flex items-center gap-2">
                                <span v-if="gen.status === 'pending'" 
                                    class="flex items-center gap-1.5 text-sm text-yellow-700 bg-yellow-50 px-2.5 py-1 rounded-full">
                                    <svg class="animate-spin h-3 w-3" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                    </svg>
                                    Generating
                                </span>
                                <span v-else-if="gen.status === 'failed'" 
                                    class="text-sm text-red-700 bg-red-50 px-2.5 py-1 rounded-full">
                                    Failed
                                </span>
                                <span v-else 
                                    class="text-sm text-green-700 bg-green-50 px-2.5 py-1 rounded-full">
                                    Done
                                </span>

                                <button v-if="gen.status === 'failed'" @click="retryGeneration(gen.id)" 
                                    class="text-sm text-indigo-600 hover:underline">
                                    Try Again
                                </button>
                            </div>
                        </div>
                        <p v-if="gen.generated_content" class="text-gray-600 text-sm leading-relaxed whitespace-pre-line">
                            {{ gen.generated_content }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>