<script setup>
import AppLayout from '../../Layouts/AppLayout.vue';
import BackButton from '../../Components/BackButton.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    reclamations: Array,
    statuts: Array,
    filtres: Object,
});

const recherche = ref(props.filtres.recherche || '');
const statutSelectionne = ref(props.filtres.statut || '');

function appliquerFiltres() {
    router.get('/mes-reclamations', {
        recherche: recherche.value || undefined,
        statut: statutSelectionne.value || undefined,
    }, { preserveState: true, replace: true });
}

function formatDate(dateStr) {
    if (!dateStr) return '—';
    return new Date(dateStr).toLocaleDateString('fr-FR', {
        day: '2-digit', month: '2-digit', year: 'numeric',
    });
}

const couleurStatut = {
    'Nouvelle': 'bg-blue-100 text-blue-700',
    'En cours': 'bg-yellow-100 text-yellow-700',
    'En attente': 'bg-orange-100 text-orange-700',
    'Résolue': 'bg-green-100 text-green-700',
    'Rejetée': 'bg-red-100 text-red-700',
    'Clôturée': 'bg-gray-100 text-gray-700',
};

function estHorsDelai(reclamation) {
    if (reclamation.date_traitement) return false;
    return new Date(reclamation.date_echeance) < new Date();
}
</script>

<template>
    <AppLayout>
        <BackButton fallback="/dashboard" />

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Mes réclamations</h1>
            <Link href="/reclamations/creer" class="bg-blue-600 text-white px-4 py-2 rounded">
                + Nouvelle réclamation
            </Link>
        </div>

        <div class="bg-white p-4 rounded shadow mb-6 flex gap-3 flex-wrap">
            <input
                v-model="recherche"
                @input="appliquerFiltres"
                type="text"
                placeholder="Rechercher par objet..."
                class="border rounded px-3 py-2 flex-1 min-w-[200px]"
            />
            <select v-model="statutSelectionne" @change="appliquerFiltres" class="border rounded px-3 py-2">
                <option value="">Tous les statuts</option>
                <option v-for="s in statuts" :key="s.id" :value="s.id">{{ s.lib_stat }}</option>
            </select>
        </div>

        <div v-if="!reclamations.length" class="bg-white p-6 rounded shadow text-gray-500">
            Aucune réclamation ne correspond à votre recherche.
        </div>

        <div v-else class="space-y-3">
            <Link
                v-for="r in reclamations"
                :key="r.cod_reclam"
                :href="`/mes-reclamations/${r.cod_reclam}`"
                class="block bg-white p-4 rounded shadow hover:shadow-md transition"
            >
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-semibold">{{ r.obj_reclam }}</p>
                        <p class="text-sm text-gray-500">{{ r.type_reclamation?.lib_typ_rec }}</p>
                        <p class="text-xs text-gray-400 mt-1">Soumise le {{ formatDate(r.dat_reclam) }}</p>
                    </div>
                    <div class="text-right">
                        <span :class="['text-xs px-2 py-1 rounded-full', couleurStatut[r.statut?.lib_stat] || 'bg-gray-100']">
                            {{ r.statut?.lib_stat }}
                        </span>
                        <p v-if="estHorsDelai(r)" class="text-xs text-red-600 mt-1">Hors délai</p>
                    </div>
                </div>
            </Link>
        </div>
    </AppLayout>
</template>