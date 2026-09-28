<script setup>
import { onMounted, onBeforeUnmount, ref, watch } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps({
    labels: Array,
    valeurs: Array,
    couleur: { type: String, default: '#3b82f6' },
});

const canvas = ref(null);
let chartInstance = null;

function creerGraphique() {
    if (chartInstance) chartInstance.destroy();

    chartInstance = new Chart(canvas.value, {
        type: 'bar',
        data: {
            labels: props.labels,
            datasets: [{
                data: props.valeurs,
                backgroundColor: props.couleur,
                borderRadius: 4,
            }],
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
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