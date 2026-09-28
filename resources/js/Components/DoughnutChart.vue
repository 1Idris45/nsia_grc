<script setup>
import { onMounted, onBeforeUnmount, ref, watch } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps({
    labels: Array,
    valeurs: Array,
});

const canvas = ref(null);
let chartInstance = null;

const couleurs = ['#3b82f6', '#f59e0b', '#f97316', '#22c55e', '#ef4444', '#6b7280'];

function creerGraphique() {
    if (chartInstance) chartInstance.destroy();

    chartInstance = new Chart(canvas.value, {
        type: 'doughnut',
        data: {
            labels: props.labels,
            datasets: [{
                data: props.valeurs,
                backgroundColor: couleurs,
            }],
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
            },
        },
    });
}

onMounted(creerGraphique);
watch(() => props.valeurs, creerGraphique);
onBeforeUnmount(() => chartInstance?.destroy());
</script>

<template>
    <canvas ref="canvas"></canvas>
</template>