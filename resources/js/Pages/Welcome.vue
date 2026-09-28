<script setup>
import { Link, usePage } from '@inertiajs/vue3';

const user = usePage().props.auth.user;
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex flex-col">
        <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
            <span class="font-bold text-lg text-blue-700">NSIA GRC</span>
            <div class="flex gap-3">
                <template v-if="user">
                    <Link href="/dashboard" class="bg-blue-600 text-white px-4 py-2 rounded">
                        Mon espace
                    </Link>
                </template>
                <template v-else>
                    <Link href="/login" class="px-4 py-2 rounded border">Connexion</Link>
                    <Link href="/register" class="bg-blue-600 text-white px-4 py-2 rounded">Inscription</Link>
                </template>
            </div>
        </nav>

        <main class="flex-1 flex items-center justify-center px-6">
            <div class="max-w-xl text-center">
                <h1 class="text-3xl font-bold mb-4">Gestion des Réclamations Clients</h1>
                <p class="text-gray-600 mb-8">
                    Soumettez vos réclamations en ligne et suivez leur traitement en temps réel.
                    NSIA Vie Assurances s'engage à traiter chaque demande sous 10 jours ouvrés.
                </p>
                <div v-if="!user" class="flex gap-4 justify-center">
                    <Link href="/register" class="bg-blue-600 text-white px-6 py-3 rounded font-medium">
                        Créer un compte
                    </Link>
                    <Link href="/login" class="border px-6 py-3 rounded font-medium">
                        J'ai déjà un compte
                    </Link>
                </div>
            </div>
        </main>

        <footer class="text-center text-sm text-gray-400 py-4">
            NSIA Vie Assurances Côte d'Ivoire — Application de Gestion des Réclamations Clients
        </footer>
    </div>
</template>