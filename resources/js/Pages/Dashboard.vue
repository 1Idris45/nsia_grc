<script setup>
import AppLayout from '../Layouts/AppLayout.vue';
import { usePage, Link } from '@inertiajs/vue3';
import DoughnutChart from '../Components/DoughnutChart.vue';
import BarChart from '../Components/BarChart.vue';
import LineChart from '../Components/LineChart.vue';

const user = usePage().props.auth.user;

const props = defineProps({
    stats: Object,
});
</script>

<template>
    <AppLayout>
        <h1 class="text-2xl font-bold mb-2">Tableau de bord</h1>
        <p class="text-gray-600 mb-4">
            Connecté en tant que : {{ user.prenom_util }} {{ user.nom_util }} ({{ user.role }})
        </p>

        <Link
            v-if="user.role === 'client'"
            href="/reclamations/creer"
            class="inline-block bg-blue-600 text-white px-4 py-2 rounded mb-2 mr-2"
        >
            + Nouvelle réclamation
        </Link>
        <Link
            v-if="user.role === 'client'"
            href="/mes-notifications"
            class="inline-block border border-blue-600 text-blue-600 px-4 py-2 rounded mb-6"
        >
            Mes notifications
        </Link>

        <Link
            v-if="user.role === 'agent'"
            href="/agent/reclamations"
            class="inline-block bg-blue-600 text-white px-4 py-2 rounded mb-6"
        >
            Voir les réclamations à traiter
        </Link>

        <div v-if="user.role === 'admin'" class="flex gap-3 mb-6 flex-wrap">
            <Link href="/admin/agents" class="bg-blue-600 text-white px-4 py-2 rounded">Gérer les agents</Link>
            <Link href="/admin/clients" class="bg-blue-600 text-white px-4 py-2 rounded">Clients</Link>
            <Link href="/admin/services" class="bg-blue-600 text-white px-4 py-2 rounded">Gérer les services</Link>
            <Link href="/admin/types" class="bg-blue-600 text-white px-4 py-2 rounded">Types de réclamation</Link>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-4 rounded shadow">
                <p class="text-sm text-gray-500">Total réclamations</p>
                <p class="text-3xl font-bold">{{ stats.total_reclamations }}</p>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <p class="text-sm text-gray-500">Délai moyen (jours)</p>
                <p class="text-3xl font-bold">{{ stats.delai_moyen_jours ?? '—' }}</p>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <p class="text-sm text-gray-500">Taux hors délai</p>
                <p class="text-3xl font-bold">{{ stats.taux_hors_delai }}%</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white p-4 rounded shadow">
                <h2 class="font-semibold mb-3">Répartition par statut</h2>
                <DoughnutChart
                    v-if="stats.par_statut.length"
                    :labels="stats.par_statut.map(s => s.lib_stat)"
                    :valeurs="stats.par_statut.map(s => s.total)"
                />
                <p v-else class="text-gray-400 text-sm">Aucune donnée pour l'instant.</p>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <h2 class="font-semibold mb-3">Évolution mensuelle (6 derniers mois)</h2>
                <LineChart :labels="stats.evolution_mensuelle.labels" :valeurs="stats.evolution_mensuelle.valeurs" />
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-4 rounded shadow">
                <h2 class="font-semibold mb-3">Par service</h2>
                <BarChart
                    v-if="stats.par_service.length"
                    :labels="stats.par_service.map(s => s.lib_serv)"
                    :valeurs="stats.par_service.map(s => s.total)"
                    couleur="#3b82f6"
                />
                <p v-else class="text-gray-400 text-sm">Aucune donnée pour l'instant.</p>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <h2 class="font-semibold mb-3">Par agent</h2>
                <BarChart
                    v-if="stats.par_agent.length"
                    :labels="stats.par_agent.map(a => `${a.prenom_util} ${a.nom_util}`)"
                    :valeurs="stats.par_agent.map(a => a.total)"
                    couleur="#22c55e"
                />
                <p v-else class="text-gray-400 text-sm">Aucune donnée pour l'instant.</p>
            </div>
        </div>
    </AppLayout>
</template>