<script setup>
import AppLayout from '../../Layouts/AppLayout.vue';
import BackButton from '../../Components/BackButton.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    notifications: Array,
});

function formatDate(dateStr) {
    if (!dateStr) return '—';
    return new Date(dateStr).toLocaleDateString('fr-FR', {
        day: '2-digit', month: '2-digit', year: 'numeric',
    });
}

const libelleCanal = {
    email: 'Email',
    sms: 'SMS',
    interne: 'Notification interne',
    email_interne: 'Courrier de dépassement (Email)',
};
</script>

<template>
    <AppLayout>
        <BackButton fallback="/dashboard" />

        <h1 class="text-2xl font-bold mb-6">Mes notifications</h1>

        <div v-if="!notifications.length" class="bg-white p-6 rounded shadow text-gray-500">
            Vous n'avez reçu aucune notification pour l'instant.
        </div>

        <div v-else class="space-y-3">
            <Link
                v-for="n in notifications"
                :key="n.cod_notif"
                :href="`/mes-reclamations/${n.reclamation.cod_reclam}`"
                class="block bg-white p-4 rounded shadow hover:shadow-md transition"
            >
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">{{ n.reclamation.obj_reclam }}</p>
                        <p>{{ n.mess_notif }}</p>
                    </div>
                    <div class="text-right shrink-0 ml-4">
                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">
                            {{ libelleCanal[n.canal_notif] ?? n.canal_notif }}
                        </span>
                        <p class="text-xs text-gray-400 mt-1">{{ formatDate(n.dat_notif) }}</p>
                    </div>
                </div>
            </Link>
        </div>
    </AppLayout>
</template>