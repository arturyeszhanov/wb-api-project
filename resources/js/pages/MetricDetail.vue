<script setup>
import { ref,watch, computed } from "vue"
import HeaderInfo from "../components/HeaderInfo.vue"
import FilterPanel from "../components/FiltersPanel.vue"
import DataChart from "../components/DataChart.vue"
import DataTable from "../components/DataTable.vue"

import { useFiltersStore } from "@/stores/filters"
import { useAggregatedOrders } from "@/composables/useAggregatedOrders"

const rowsPerPage = 10

const props = defineProps({
  metricKey: { type: String, required: true },
})

const filtersStore = useFiltersStore()

// =======================
// Используем агрегатор
const { allByMetric, loading } = useAggregatedOrders(filtersStore.filters, [props.metricKey])
const currentPageFirst = ref(0)

// строки для таблицы — берем агрегированные данные по выбранной метрике
const tableRows = computed(() => allByMetric.value[props.metricKey] || [])

// график только по текущей странице таблицы
const chartData = computed(() => {
  const slice = tableRows.value.slice(currentPageFirst.value, currentPageFirst.value + rowsPerPage)
  return {
    labels: slice.map(r => r.nm_id),
    datasets: [
      {
        label: "Предыдущий период",
        data: slice.map(r => r.previous),
        backgroundColor: "rgba(255,99,132,0.6)",
      },
      {
        label: "Текущий период",
        data: slice.map(r => r.current),
        backgroundColor: "rgba(66,165,245,0.6)",
      },
    ],
  }
} )

// пагинация
function onPageChange(first) {
  currentPageFirst.value = first
}
</script>

<template>
  <HeaderInfo />

  <FilterPanel
    :regions="[]"
    :categories="[]"
    :brands="[]"
  />

  <div class="w-[90%] mx-auto grid grid-cols-1 md:grid-cols-2 gap-3 items-stretch rounded-md bg-white shadow-xl mb-12 mt-12">
    <DataChart :data="tableRows.slice(currentPageFirst, currentPageFirst + rowsPerPage)" />
    <DataTable :data="tableRows" :rows="rowsPerPage" @page-change="onPageChange" />
  </div>
</template>

<style>
canvas {
  image-rendering: pixelated;
}
</style>
