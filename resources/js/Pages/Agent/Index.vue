<script setup>
import AppLayout from '../../Layouts/AppLayout.vue';
import BackButton from '../../Components/BackButton.vue';
import { useForm, usePage, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    reclamations: Array,
    statuts: Array,
    services: Array,
    filtres: Object,
});

const page = usePage();
const recherche = ref(props.filtres.recherche || '');
const statutSelectionne = ref(props.filtres.statut || '');
const serviceSelectionne = ref(props.filtres.service || '');

function appliquerFiltres() {
    router.get('/agent/reclamations', {
        recherche: recherche.value || undefined,
        statut: statutSelectionne.value || undefined,
        service: serviceSelectionne.value || undefined,
    }, { preserveState: true, replace: true });
}

function formatDate(dateStr) {
    if (!dateStr) return '—';
    return new Date(dateStr).toLocaleDateString('fr-FR', {
        day: '2-digit', month: '2-digit', year: 'numeric',
    });
}

function estHorsDelai(r) {
    if (r.date_traitement) return false;
    return new Date(r.date_echeance) < new Date();
}

function changerStatut(reclamation, nouveauStatutId) {
    const form = useForm({ id_stat: nouveauStatutId });
    form.patch(`/agent/reclamations/${reclamation.cod_reclam}/statut`, {
        preserveScroll: true,
    });
}
</script>

<template>
    <AppLayout>
        <BackButton fallback="/dashboard" />

        <h1 class="text-2xl font-bold mb-6">Réclamations à traiter</h1>

        <div v-if="page.props.flash?.success" class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ page.props.flash.success }}
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
            <select v-if="services.length" v-model="serviceSelectionne" @change="appliquerFiltres" class="border rounded px-3 py-2">
                <option value="">Tous les services</option>
                <option v-for="s in services" :key="s.id" :value="s.id">{{ s.lib_serv }}</option>
            </select>
        </div>

        <div v-if="!reclamations.length" class="bg-white p-6 rounded shadow text-gray-500">
            Aucune réclamation ne correspond à votre recherche.
        </div>

        <div v-else class="space-y-3">
            <div
                v-for="r in reclamations"
                :key="r.cod_reclam"
                class="bg-white p-4 rounded shadow"
            >
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <p class="font-semibold">{{ r.obj_reclam }}</p>
                        <p class="text-sm text-gray-500">
                            {{ r.client?.utilisateur?.prenom_util }} {{ r.client?.utilisateur?.nom_util }}
                            — {{ r.type_reclamation?.lib_typ_rec }}
                        </p>
                        <p class="text-xs text-gray-400">
                            Soumise le {{ formatDate(r.dat_reclam) }} · Service : {{ r.service?.lib_serv ?? 'Non affecté' }}
                        </p>
                    </div>
                    <p v-if="estHorsDelai(r)" class="text-xs text-red-600 font-medium">Hors délai</p>
                </div>

                <p class="text-sm text-gray-700 mb-3">{{ r.des_reclam }}</p>

                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-500">Statut :</label>
                    <select
                        :value="r.id_stat"
                        @change="changerStatut(r, $event.target.value)"
                        class="border rounded px-2 py-1 text-sm"
                    >
                        <option v-for="s in statuts" :key="s.id" :value="s.id">{{ s.lib_stat }}</option>
                    </select>
                </div>
            </div>
        </div>
    </AppLayout>
</template>