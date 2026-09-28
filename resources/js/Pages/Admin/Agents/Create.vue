<script setup>
import BackButton from '../../../Components/BackButton.vue';
import AppLayout from '../../../Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    services: Array,
});

const form = useForm({
    nom_util: '',
    prenom_util: '',
    tel_util: '',
    email_util: '',
    password: '',
    mat_agt: '',
    type_agent: 'specifique',
    id_serv: '',
});

function submit() {
    form.post('/admin/agents');
}
</script>

<template>
    <AppLayout>
        <BackButton fallback="/dashboard" />
        <h1 class="text-2xl font-bold mb-6">Nouvel agent</h1>

        <form @submit.prevent="submit" class="bg-white p-6 rounded shadow max-w-lg space-y-4">
            <div>
                <label class="block text-sm mb-1">Nom</label>
                <input v-model="form.nom_util" class="w-full border rounded px-3 py-2" required />
                <p v-if="form.errors.nom_util" class="text-red-600 text-sm mt-1">{{ form.errors.nom_util }}</p>
            </div>

            <div>
                <label class="block text-sm mb-1">Prénom</label>
                <input v-model="form.prenom_util" class="w-full border rounded px-3 py-2" required />
                <p v-if="form.errors.prenom_util" class="text-red-600 text-sm mt-1">{{ form.errors.prenom_util }}</p>
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
                <label class="block text-sm mb-1">Mot de passe temporaire</label>
                <input v-model="form.password" type="password" class="w-full border rounded px-3 py-2" required />
                <p v-if="form.errors.password" class="text-red-600 text-sm mt-1">{{ form.errors.password }}</p>
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
                <p v-if="form.errors.id_serv" class="text-red-600 text-sm mt-1">{{ form.errors.id_serv }}</p>
            </div>

            <button type="submit" :disabled="form.processing" class="w-full bg-blue-600 text-white py-2 rounded disabled:opacity-50">
                Créer l'agent
            </button>
        </form>
    </AppLayout>
</template>