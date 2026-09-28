<script setup>
import AppLayout from '../../../Layouts/AppLayout.vue';
import { Link, usePage } from '@inertiajs/vue3';

defineProps({
    agents: Array,
});

const page = usePage();
</script>

<template>
    <AppLayout>
        <div v-if="page.props.flash?.success" class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ page.props.flash.success }}
        </div>

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Gestion des agents</h1>
            <Link href="/admin/agents/creer" class="bg-blue-600 text-white px-4 py-2 rounded">
                + Nouvel agent
            </Link>
        </div>

        <div class="bg-white rounded shadow divide-y">
            <div v-for="a in agents" :key="a.id" class="p-4 flex justify-between items-center">
                <div>
                    <p class="font-semibold">{{ a.utilisateur.prenom_util }} {{ a.utilisateur.nom_util }}</p>
                    <p class="text-sm text-gray-500">
                        {{ a.utilisateur.email_util }} — Matricule {{ a.mat_agt }}
                    </p>
                    <p class="text-xs text-gray-400">
                        {{ a.type_agent === 'general' ? 'Agent Général' : `Agent Spécifique — ${a.service?.lib_serv}` }}
                    </p>
                </div>
                <Link :href="`/admin/agents/${a.id}/modifier`" class="text-blue-600 text-sm">
                    Modifier
                </Link>
            </div>
        </div>
    </AppLayout>
</template>