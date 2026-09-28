<script setup>
import AppLayout from '../../../Layouts/AppLayout.vue';
import BackButton from '../../../Components/BackButton.vue';

const props = defineProps({
    client: Object,
    reclamations: Array,
});

function formatDate(dateStr) {
    if (!dateStr) return '—';
    return new Date(dateStr).toLocaleDateString('fr-FR', {
        day: '2-digit', month: '2-digit', year: 'numeric',
    });
}
</script>

<template>
    <AppLayout>
        <BackButton fallback="/admin/clients" />

        <h1 class="text-2xl font-bold mb-1">{{ client.utilisateur.prenom_util }} {{ client.utilisateur.nom_util }}</h1>
        <p class="text-gray-500 mb-6">{{ client.utilisateur.email_util }} — {{ client.utilisateur.tel_util ?? 'Pas de téléphone' }}</p>

        <h2 class="font-semibold mb-3">Réclamations soumises</h2>

        <div v-if="!reclamations.length" class="bg-white p-6 rounded shadow text-gray-500">
            Ce client n'a soumis aucune réclamation.
        </div>

        <div v-else class="bg-white rounded shadow divide-y">
            <div v-for="r in reclamations" :key="r.cod_reclam" class="p-4 flex justify-between items-center">
                <div>
                    <p class="font-semibold">{{ r.obj_reclam }}</p>
                    <p class="text-sm text-gray-500">{{ r.type_reclamation?.lib_typ_rec }}</p>
                    <p class="text-xs text-gray-400">Soumise le {{ formatDate(r.dat_reclam) }}</p>
                </div>
                <span class="text-sm font-medium">{{ r.statut?.lib_stat }}</span>
            </div>
        </div>
    </AppLayout>
</template>