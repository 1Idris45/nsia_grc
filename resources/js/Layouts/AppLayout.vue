<script setup>
import { usePage, Link, router } from '@inertiajs/vue3';

const page = usePage();
const user = page.props.auth.user;

function logout() {
    router.post('/logout');
}
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
            <Link href="/dashboard" class="font-bold text-lg">NSIA GRC</Link>
            <div class="flex items-center gap-4">
                <Link v-if="user" href="/profil" class="text-sm text-gray-700 hover:text-blue-600">
                    {{ user.prenom_util }} {{ user.nom_util }}
                </Link>
                <button v-if="user" @click="logout" class="text-red-600">Déconnexion</button>
            </div>
        </nav>
        <main class="p-6">
            <slot />
        </main>
    </div>
</template>