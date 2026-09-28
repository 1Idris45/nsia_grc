<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    email_util: '',
    password: '',
});

function submit() {
    form.post('/login', {
        onError: () => form.reset('password'),
    });
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50">
        <div class="bg-white p-8 rounded shadow w-full max-w-sm">
            <h1 class="text-xl font-bold mb-6">Connexion — NSIA GRC</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm mb-1">Email</label>
                    <input
                        v-model="form.email_util"
                        type="email"
                        class="w-full border rounded px-3 py-2"
                        required
                    />
                    <p v-if="form.errors.email_util" class="text-red-600 text-sm mt-1">
                        {{ form.errors.email_util }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm mb-1">Mot de passe</label>
                    <input
                        v-model="form.password"
                        type="password"
                        class="w-full border rounded px-3 py-2"
                        required
                    />
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full bg-blue-600 text-white py-2 rounded disabled:opacity-50"
                >
                    Se connecter
                </button>
            </form>

            <p class="text-sm mt-4 text-center">
                Pas encore de compte ?
                <a href="/register" class="text-blue-600">Inscrivez-vous</a>
            </p>
        </div>
    </div>
</template>