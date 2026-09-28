<script setup>
import AppLayout from '../../../Layouts/AppLayout.vue';
import BackButton from '../../../Components/BackButton.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    clients: Array,
    filtres: Object,
});

const recherche = ref(props.filtres.recherche || '');

function appliquerFiltres() {
    router.get('/admin/clients', {
        recherche: recherche.value || undefined,
    }, { preserveState: true, replace: true });
}
</script>

<template>
    <AppLayout>
        <BackButton fallback="/dashboard" />

        <h1 class="text-2xl font-bold mb-6">Clients</h1>

        <input
            v-model="recherche"
            @input="appliquerFiltres"
            type="text"
            placeholder="Rechercher par nom, prénom ou email..."
            class="w-full border rounded px-3 py-2 mb-6"
        />

        <div v-if="!clients.length" class="bg-white p-6 rounded shadow text-gray-500">
            Aucun client trouvé.
        </div>

        <div v-else class="bg-white rounded shadow divide-y">
            <Link
                v-for="c in clients"
                :key="c.id"
                :href="`/admin/clients/${c.id}`"
                class="p-4 flex justify-between items-center hover:bg-gray-50"
            >
                <div>
                    <p class="font-semibold">{{ c.utilisateur.prenom_util }} {{ c.utilisateur.nom_util }}</p>
                    <p class="text-sm text-gray-500">{{ c.utilisateur.email_util }}</p>
                </div>
                <span class="text-sm text-gray-500">{{ c.reclamations_count }} réclamation(s)</span>
            </Link>
        </div>
    </AppLayout>
</template>