<script setup>
import BackButton from '../../Components/BackButton.vue';
import AppLayout from '../../Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    types: Array,
});

const form = useForm({
    obj_reclam: '',
    des_reclam: '',
    id_typ_rec: '',
    pieces_jointes: [],
});

function handleFiles(event) {
    form.pieces_jointes = Array.from(event.target.files);
}

function submit() {
    form.post('/reclamations', {
        forceFormData: true,
    });
}
</script>

<template>
    <AppLayout>
        <BackButton fallback="/dashboard" />
        <h1 class="text-2xl font-bold mb-6">Nouvelle réclamation</h1>

        <form @submit.prevent="submit" class="bg-white p-6 rounded shadow max-w-lg space-y-4">
            <div>
                <label class="block text-sm mb-1">Objet</label>
                <input v-model="form.obj_reclam" class="w-full border rounded px-3 py-2" required />
                <p v-if="form.errors.obj_reclam" class="text-red-600 text-sm mt-1">{{ form.errors.obj_reclam }}</p>
            </div>

            <div>
                <label class="block text-sm mb-1">Type de réclamation</label>
                <select v-model="form.id_typ_rec" class="w-full border rounded px-3 py-2" required>
                    <option value="" disabled>Sélectionnez un type</option>
                    <option v-for="t in types" :key="t.id" :value="t.id">{{ t.lib_typ_rec }}</option>
                </select>
                <p v-if="form.errors.id_typ_rec" class="text-red-600 text-sm mt-1">{{ form.errors.id_typ_rec }}</p>
            </div>

            <div>
                <label class="block text-sm mb-1">Description</label>
                <textarea v-model="form.des_reclam" rows="4" class="w-full border rounded px-3 py-2" required></textarea>
                <p v-if="form.errors.des_reclam" class="text-red-600 text-sm mt-1">{{ form.errors.des_reclam }}</p>
            </div>

            <div>
                <label class="block text-sm mb-1">Pièces jointes (PDF, JPG, PNG — 5 Mo max)</label>
                <input type="file" multiple accept=".pdf,.jpg,.jpeg,.png" @change="handleFiles" class="w-full" />
                <p v-if="form.errors['pieces_jointes.0']" class="text-red-600 text-sm mt-1">
                    {{ form.errors['pieces_jointes.0'] }}
                </p>
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="w-full bg-blue-600 text-white py-2 rounded disabled:opacity-50"
            >
                Soumettre
            </button>
        </form>
    </AppLayout>
</template>