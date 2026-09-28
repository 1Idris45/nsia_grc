<script setup>
import AppLayout from '../../../Layouts/AppLayout.vue';
import BackButton from '../../../Components/BackButton.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    services: Array,
});

const page = usePage();
const editingId = ref(null);

const createForm = useForm({ lib_serv: '', des_serv: '' });
const editForm = useForm({ lib_serv: '', des_serv: '' });

function submitCreate() {
    createForm.post('/admin/services', {
        onSuccess: () => createForm.reset(),
    });
}

function startEdit(service) {
    editingId.value = service.id;
    editForm.lib_serv = service.lib_serv;
    editForm.des_serv = service.des_serv;
}

function submitEdit(id) {
    editForm.put(`/admin/services/${id}`, {
        onSuccess: () => (editingId.value = null),
    });
}

function supprimer(id) {
    if (confirm('Supprimer ce service ?')) {
        useForm({}).delete(`/admin/services/${id}`);
    }
}
</script>

<template>
    <AppLayout>
        <BackButton fallback="/dashboard" />

        <div v-if="page.props.flash?.success" class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ page.props.flash.success }}
        </div>
        <div v-if="Object.keys(page.props.errors || {}).length" class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ Object.values(page.props.errors)[0] }}
        </div>

        <h1 class="text-2xl font-bold mb-6">Services</h1>

        <form @submit.prevent="submitCreate" class="bg-white p-4 rounded shadow mb-6 flex gap-3 items-start">
            <div class="flex-1">
                <input v-model="createForm.lib_serv" placeholder="Libellé du service" class="w-full border rounded px-3 py-2 mb-2" required />
                <input v-model="createForm.des_serv" placeholder="Description (optionnel)" class="w-full border rounded px-3 py-2" />
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Ajouter</button>
        </form>

        <div class="bg-white rounded shadow divide-y">
            <div v-for="s in services" :key="s.id" class="p-4">
                <div v-if="editingId === s.id" class="space-y-2">
                    <input v-model="editForm.lib_serv" class="w-full border rounded px-3 py-2" />
                    <input v-model="editForm.des_serv" class="w-full border rounded px-3 py-2" />
                    <div class="flex gap-2">
                        <button @click="submitEdit(s.id)" class="bg-blue-600 text-white px-3 py-1 rounded text-sm">Enregistrer</button>
                        <button @click="editingId = null" class="text-sm text-gray-500">Annuler</button>
                    </div>
                </div>
                <div v-else class="flex justify-between items-center">
                    <div>
                        <p class="font-semibold">{{ s.lib_serv }}</p>
                        <p class="text-sm text-gray-500">{{ s.des_serv }}</p>
                        <p class="text-xs text-gray-400">{{ s.types_reclamation_count }} type(s) de réclamation associé(s)</p>
                    </div>
                    <div class="flex gap-3">
                        <button @click="startEdit(s)" class="text-blue-600 text-sm">Modifier</button>
                        <button @click="supprimer(s.id)" class="text-red-600 text-sm">Supprimer</button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>