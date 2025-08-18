<template>
  <div class="h-full">
    <Chart
      :type="chartType"
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
  data: { type: Object, required: true },
  chartType: { type: String, default: "bar" },
  title: { type: String, default: "" }
});

const chartData = ref({ labels: [], datasets: [] });

const chartOptions = ref({
  responsive: true,
  maintainAspectRatio: false,
  devicePixelRatio: window.devicePixelRatio || 1,
  plugins: {
    legend: {
      labels: { usePointStyle: true, pointStyle: "circle", boxWidth: 10, padding: 15 },
    },
    title: {
      display: !!props.title,
      text: props.title,
      font: { size: 16 },
    },
  },
  scales: {
    x: { ticks: { color: "#333", padding: 10 } },
    y: { ticks: { color: "#333", padding: 10 } },
  },
});

watch(
  () => props.data,
  (newData) => {
    if (!newData || !newData.labels || !newData.datasets) {
      chartData.value = { labels: [], datasets: [] };
      return;
    }

    chartData.value = {
      labels: newData.labels,
      datasets: newData.datasets.map(ds => ({
        ...ds,
        fill: false,
        tension: 0.3,
      })),
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
