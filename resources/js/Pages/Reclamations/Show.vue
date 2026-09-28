<script setup>
import AppLayout from '../../Layouts/AppLayout.vue';
import BackButton from '../../Components/BackButton.vue';

const props = defineProps({
    reclamation: Object,
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
        <BackButton fallback="/mes-reclamations" />

        <div class="flex justify-between items-start mb-2">
            <div>
                <h1 class="text-2xl font-bold">{{ reclamation.obj_reclam }}</h1>
                <p class="text-gray-500">{{ reclamation.type_reclamation?.lib_typ_rec }}</p>
            </div>
            
                <a :href="`/mes-reclamations/${reclamation.cod_reclam}/pdf`" class="bg-gray-800 text-white px-4 py-2 rounded text-sm">
                    Télécharger en PDF
                </a>
        </div>

        <div class="bg-white p-6 rounded shadow mb-6 mt-4">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-sm text-gray-500">Statut</p>
                    <p class="font-semibold">{{ reclamation.statut?.lib_stat }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Service</p>
                    <p class="font-semibold">{{ reclamation.service?.lib_serv ?? 'Non affecté' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Date de soumission</p>
                    <p class="font-semibold">{{ formatDate(reclamation.dat_reclam) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Échéance (10 j. ouvrés)</p>
                    <p class="font-semibold">{{ formatDate(reclamation.date_echeance) }}</p>
                </div>
            </div>

            <div>
                <p class="text-sm text-gray-500 mb-1">Description</p>
                <p>{{ reclamation.des_reclam }}</p>
            </div>
        </div>

        <div v-if="reclamation.pieces_jointes?.length" class="bg-white p-6 rounded shadow mb-6">
            <h2 class="font-semibold mb-3">Pièces jointes</h2>
            <ul>
                <li v-for="p in reclamation.pieces_jointes" :key="p.id_piec" class="py-1">
                    <a :href="`/storage/${p.chem_fichpiec}`" target="_blank" class="text-blue-600 underline">
                        {{ p.nom_fich_piec }}
                    </a>
                </li>
            </ul>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <h2 class="font-semibold mb-3">Historique</h2>
            <ul class="space-y-2">
                <li v-for="h in reclamation.historiques" :key="h.id_histor" class="border-b pb-2 last:border-0">
                    <p class="text-sm font-medium">{{ h.act_ehistor }}</p>
                    <p class="text-xs text-gray-500">{{ h.dat_act_histor }}</p>
                    <p class="text-xs text-gray-400">{{ h.heure_act_histor }}</p>
                </li>
            </ul>
        </div>
    </AppLayout>
</template>