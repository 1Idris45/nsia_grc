<script setup>
import AppLayout from '../../../Layouts/AppLayout.vue';
import BackButton from '../../../Components/BackButton.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    agent: Object,
    services: Array,
});

const form = useForm({
    nom_util: props.agent.utilisateur.nom_util,
    prenom_util: props.agent.utilisateur.prenom_util,
    tel_util: props.agent.utilisateur.tel_util,
    email_util: props.agent.utilisateur.email_util,
    mat_agt: props.agent.mat_agt,
    type_agent: props.agent.type_agent,
    id_serv: props.agent.id_serv ?? '',
});

function submit() {
    form.put(`/admin/agents/${props.agent.id}`);
}
</script>

<template>
    <AppLayout>
        <BackButton fallback="/admin/agents" />

        <h1 class="text-2xl font-bold mb-6">Modifier l'agent</h1>

        <form @submit.prevent="submit" class="bg-white p-6 rounded shadow max-w-lg space-y-4">
            <div>
                <label class="block text-sm mb-1">Nom</label>
                <input v-model="form.nom_util" class="w-full border rounded px-3 py-2" required />
            </div>

            <div>
                <label class="block text-sm mb-1">Prénom</label>
                <input v-model="form.prenom_util" class="w-full border rounded px-3 py-2" required />
            </div>

            <div>
                <label class="block text-sm mb-1">Téléphone</label>
                <input v-model="form.tel_util" class="w-full border rounded px-3 py-2" />
            </div>

            <div>
                <label class="block text-sm mb-1">Email</label>
                <input v-model="form.email_util" type="email" class="w-full border rounded px-3 py-2" required />
                <p v-if="form.errors.email_util" class="text-red-600 text-sm mt-1">{{ form.errors.email_util }}</p>
            </div>

            <div>
                <label class="block text-sm mb-1">Matricule</label>
                <input v-model="form.mat_agt" class="w-full border rounded px-3 py-2" required />
                <p v-if="form.errors.mat_agt" class="text-red-600 text-sm mt-1">{{ form.errors.mat_agt }}</p>
            </div>

            <div>
                <label class="block text-sm mb-1">Type d'agent</label>
                <select v-model="form.type_agent" class="w-full border rounded px-3 py-2">
                    <option value="specifique">Agent Spécifique</option>
                    <option value="general">Agent Général</option>
                </select>
            </div>

            <div v-if="form.type_agent === 'specifique'">
                <label class="block text-sm mb-1">Service</label>
                <select v-model="form.id_serv" class="w-full border rounded px-3 py-2" required>
                    <option value="" disabled>Sélectionnez un service</option>
                    <option v-for="s in services" :key="s.id" :value="s.id">{{ s.lib_serv }}</option>
                </select>
            </div>

            <button type="submit" :disabled="form.processing" class="w-full bg-blue-600 text-white py-2 rounded disabled:opacity-50">
                Enregistrer
            </button>
        </form>
    </AppLayout>
</template>