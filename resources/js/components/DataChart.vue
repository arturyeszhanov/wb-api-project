<template>
    <div class="h-full">
        <Chart
            type="bar"
            :data="chartData"
            :options="chartOptions"
            class="h-full ps-1"
        />
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import Chart from "primevue/chart";

const props = defineProps({
    data: {
        type: Array,
        default: () => []
    },
});

const chartData = ref({
    labels: [],
    datasets: [
        {
            label: "Предыдущий период",
            backgroundColor: "rgba(240, 52, 158, 0.8)",
            borderColor: "rgba(240, 52, 158, 1)",
            borderWidth: 2,
            data: [],
        },
        {
            label: "Текущий период",
            backgroundColor: "rgba(139, 17, 215, 0.8)",
            borderColor: "rgba(139, 17, 215, 1)",
            borderWidth: 2,
            data: [],
        },
    ],
});

const chartOptions = ref({
    responsive: true,
    maintainAspectRatio: false,
    devicePixelRatio: window.devicePixelRatio || 1,
    onHover: (event, chartElement) => {
        event.native.target.style.cursor = chartElement.length ? "pointer" : "default";
    },
    plugins: {
        legend: {
            labels: {
                usePointStyle: true,
                pointStyle: "circle",
                boxWidth: 10,
                padding: 15,
            },
        },
    },
    scales: {
        x: {
            ticks: {
                color: "#333",
                padding: 10,
            },
        },
    },
});

// Следим за входящими данными и делаем локальную копию
watch(
    () => props.data,
    (newData) => {
        if (!Array.isArray(newData) || newData.length === 0) {
            chartData.value.labels = [];
            chartData.value.datasets[0].data = [];
            chartData.value.datasets[1].data = [];
            return;
        }

        // Копируем в локальный стейт
        chartData.value = {
            labels: newData.map((item) => item.nm_id),
            datasets: [
                {
                    ...chartData.value.datasets[0],
                    data: newData.map((item) => item.previous),
                },
                {
                    ...chartData.value.datasets[1],
                    data: newData.map((item) => item.current),
                },
            ],
        };
    },
    { immediate: true }
);
</script>

<style>
canvas {
    image-rendering: pixelated;
}
</style>
