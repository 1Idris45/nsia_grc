<script setup>
import AppLayout from '../../Layouts/AppLayout.vue';
import BackButton from '../../Components/BackButton.vue';
import { useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    utilisateur: Object,
});

const page = usePage();

const infoForm = useForm({
    nom_util: props.utilisateur.nom_util,
    prenom_util: props.utilisateur.prenom_util,
    tel_util: props.utilisateur.tel_util,
    adres_util: props.utilisateur.adres_util,
    email_util: props.utilisateur.email_util,
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

function submitInfo() {
    infoForm.put('/profil');
}

function submitPassword() {
    passwordForm.put('/profil/mot-de-passe', {
        onSuccess: () => passwordForm.reset(),
    });
}

function libelleRole() {
    if (props.utilisateur.role === 'client') return 'Client';
    if (props.utilisateur.role === 'admin') return 'Administrateur';
    if (props.utilisateur.agent?.type_agent === 'general') return 'Agent Général';
    return `Agent Spécifique — ${props.utilisateur.agent?.service?.lib_serv ?? ''}`;
}
</script>

<template>
    <AppLayout>
        <BackButton fallback="/dashboard" />

        <div v-if="page.props.flash?.success" class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ page.props.flash.success }}
        </div>

        <h1 class="text-2xl font-bold mb-1">Mon profil</h1>
        <p class="text-gray-500 mb-6">{{ libelleRole() }}</p>

        <div class="bg-white p-6 rounded shadow max-w-lg mb-6">
            <h2 class="font-semibold mb-4">Informations personnelles</h2>
            <form @submit.prevent="submitInfo" class="space-y-4">
                <div>
                    <label class="block text-sm mb-1">Nom</label>
                    <input v-model="infoForm.nom_util" class="w-full border rounded px-3 py-2" required />
                </div>
                <div>
                    <label class="block text-sm mb-1">Prénom</label>
                    <input v-model="infoForm.prenom_util" class="w-full border rounded px-3 py-2" required />
                </div>
                <div>
                    <label class="block text-sm mb-1">Téléphone</label>
                    <input v-model="infoForm.tel_util" class="w-full border rounded px-3 py-2" />
                </div>
                <div>
                    <label class="block text-sm mb-1">Adresse</label>
                    <input v-model="infoForm.adres_util" class="w-full border rounded px-3 py-2" />
                </div>
                <div>
                    <label class="block text-sm mb-1">Email</label>
                    <input v-model="infoForm.email_util" type="email" class="w-full border rounded px-3 py-2" required />
                    <p v-if="infoForm.errors.email_util" class="text-red-600 text-sm mt-1">{{ infoForm.errors.email_util }}</p>
                </div>
                <button type="submit" :disabled="infoForm.processing" class="bg-blue-600 text-white px-4 py-2 rounded disabled:opacity-50">
                    Enregistrer
                </button>
            </form>
        </div>

        <div class="bg-white p-6 rounded shadow max-w-lg">
            <h2 class="font-semibold mb-4">Changer le mot de passe</h2>
            <form @submit.prevent="submitPassword" class="space-y-4">
                <div>
                    <label class="block text-sm mb-1">Mot de passe actuel</label>
                    <input v-model="passwordForm.current_password" type="password" class="w-full border rounded px-3 py-2" required />
                    <p v-if="passwordForm.errors.current_password" class="text-red-600 text-sm mt-1">{{ passwordForm.errors.current_password }}</p>
                </div>
                <div>
                    <label class="block text-sm mb-1">Nouveau mot de passe</label>
                    <input v-model="passwordForm.password" type="password" class="w-full border rounded px-3 py-2" required />
                    <p v-if="passwordForm.errors.password" class="text-red-600 text-sm mt-1">{{ passwordForm.errors.password }}</p>
                </div>
                <div>
                    <label class="block text-sm mb-1">Confirmer le nouveau mot de passe</label>
                    <input v-model="passwordForm.password_confirmation" type="password" class="w-full border rounded px-3 py-2" required />
                </div>
                <button type="submit" :disabled="passwordForm.processing" class="bg-blue-600 text-white px-4 py-2 rounded disabled:opacity-50">
                    Changer le mot de passe
                </button>
            </form>
        </div>
    </AppLayout>
</template>