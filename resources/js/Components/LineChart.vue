<script setup>
import { onMounted, onBeforeUnmount, ref, watch } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps({
    labels: Array,
    valeurs: Array,
});

const canvas = ref(null);
let chartInstance = null;

function creerGraphique() {
    if (chartInstance) chartInstance.destroy();

    chartInstance = new Chart(canvas.value, {
        type: 'line',
        data: {
            labels: props.labels,
            datasets: [{
                data: props.valeurs,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.3,
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