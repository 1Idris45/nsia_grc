<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    status: Number,
});

const messages = {
    404: {
        titre: 'Page introuvable',
        texte: "La page que vous cherchez n'existe pas ou a été déplacée.",
    },
    403: {
        titre: 'Accès refusé',
        texte: "Vous n'avez pas les droits nécessaires pour accéder à cette page.",
    },
    500: {
        titre: 'Erreur serveur',
        texte: "Une erreur inattendue s'est produite. Notre équipe a été informée.",
    },
    503: {
        titre: 'Service indisponible',
        texte: "L'application est temporairement en maintenance. Merci de réessayer plus tard.",
    },
};

const contenu = messages[props.status] ?? {
    titre: 'Erreur',
    texte: "Une erreur inattendue s'est produite.",
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex items-center justify-center px-6">
        <div class="text-center max-w-md">
            <p class="text-6xl font-bold text-blue-600 mb-4">{{ status }}</p>
            <h1 class="text-2xl font-bold mb-2">{{ contenu.titre }}</h1>
            <p class="text-gray-500 mb-8">{{ contenu.texte }}</p>
            <Link href="/dashboard" class="bg-blue-600 text-white px-6 py-3 rounded font-medium">
                Retour à l'accueil
            </Link>
        </div>
    </div>
</template>