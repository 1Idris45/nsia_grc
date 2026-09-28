<script setup>
import AppLayout from '../../../Layouts/AppLayout.vue';
import BackButton from '../../../Components/BackButton.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    types: Array,
    services: Array,
});

const page = usePage();
const editingId = ref(null);

const createForm = useForm({ lib_typ_rec: '', des_typ_rec: '', id_serv: '' });
const editForm = useForm({ lib_typ_rec: '', des_typ_rec: '', id_serv: '' });

function submitCreate() {
    createForm.post('/admin/types', {
        onSuccess: () => createForm.reset(),
    });
}

function startEdit(type) {
    editingId.value = type.id;
    editForm.lib_typ_rec = type.lib_typ_rec;
    editForm.des_typ_rec = type.des_typ_rec;
    editForm.id_serv = type.id_serv;
}

function submitEdit(id) {
    editForm.put(`/admin/types/${id}`, {
        onSuccess: () => (editingId.value = null),
    });
}

function supprimer(id) {
    if (confirm('Supprimer ce type de réclamation ?')) {
        useForm({}).delete(`/admin/types/${id}`);
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

        <h1 class="text-2xl font-bold mb-6">Types de réclamation</h1>

        <form @submit.prevent="submitCreate" class="bg-white p-4 rounded shadow mb-6 space-y-2">
            <input v-model="createForm.lib_typ_rec" placeholder="Libellé du type" class="w-full border rounded px-3 py-2" required />
            <input v-model="createForm.des_typ_rec" placeholder="Description (optionnel)" class="w-full border rounded px-3 py-2" />
            <select v-model="createForm.id_serv" class="w-full border rounded px-3 py-2">
                <option value="">Aucun service par défaut</option>
                <option v-for="s in services" :key="s.id" :value="s.id">{{ s.lib_serv }}</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Ajouter</button>
        </form>

        <div class="bg-white rounded shadow divide-y">
            <div v-for="t in types" :key="t.id" class="p-4">
                <div v-if="editingId === t.id" class="space-y-2">
                    <input v-model="editForm.lib_typ_rec" class="w-full border rounded px-3 py-2" />
                    <input v-model="editForm.des_typ_rec" class="w-full border rounded px-3 py-2" />
                    <select v-model="editForm.id_serv" class="w-full border rounded px-3 py-2">
                        <option value="">Aucun service par défaut</option>
                        <option v-for="s in services" :key="s.id" :value="s.id">{{ s.lib_serv }}</option>
                    </select>
                    <div class="flex gap-2">
                        <button @click="submitEdit(t.id)" class="bg-blue-600 text-white px-3 py-1 rounded text-sm">Enregistrer</button>
                        <button @click="editingId = null" class="text-sm text-gray-500">Annuler</button>
                    </div>
                </div>
                <div v-else class="flex justify-between items-center">
                    <div>
                        <p class="font-semibold">{{ t.lib_typ_rec }}</p>
                        <p class="text-xs text-gray-400">
                            Service par défaut : {{ t.service?.lib_serv ?? 'Aucun' }}
                        </p>
                    </div>
                    <div class="flex gap-3">
                        <button @click="startEdit(t)" class="text-blue-600 text-sm">Modifier</button>
                        <button @click="supprimer(t.id)" class="text-red-600 text-sm">Supprimer</button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>