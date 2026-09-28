<script setup>
import { router } from '@inertiajs/vue3';

defineProps({
    fallback: { type: String, default: '/dashboard' },
});

function goBack(fallback) {
    if (window.history.length > 1) {
        window.history.back();
    } else {
        router.visit(fallback);
    }
}
</script>

<template>
    <button
        @click="goBack(fallback)"
        class="flex items-center gap-1 text-sm text-gray-600 hover:text-gray-900 mb-4"
    >
        ← Retour
    </button>
</template>