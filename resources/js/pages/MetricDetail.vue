<template>
  <HeaderInfo />

  <FilterPanel
    :regions="[]"
    :categories="[]"
    :brands="[]"
  />
    <div class="w-[90%] mx-auto flex justify-start ps-60 text-gray-900 mt-10">
    <h2 class="text-2xl md:text-2xl font-bold text-gray-800">
        {{ pageTitle }}
    </h2>
    </div>
    <div class="w-[90%] mx-auto flex flex-col md:flex-row gap-3 mb-12 mt-2">
  <div class="flex-1 bg-white rounded-md shadow-xl p-3">
    <DataChart :data="tableRows.slice(currentPageFirst, currentPageFirst + rowsPerPage)" />
  </div>
  <div class="flex-1 bg-white rounded-md shadow-xl p-3">
    <DataTable :data="tableRows" :rows="rowsPerPage" @page-change="onPageChange" />
  </div>
</div>

</template>

<script setup>
import { ref,watch, computed } from "vue"
import HeaderInfo from "../components/HeaderInfo.vue"
import FilterPanel from "../components/FiltersPanel.vue"
import DataChart from "../components/DataChart.vue"
import DataTable from "../components/DataTable.vue"
import { useFiltersStore } from "@/stores/filters"
import { useAggregatedOrders } from "@/composables/useAggregatedOrders"

const metricNames = {
    total_price: "Сумма продаж",
    discount_percent: "Скидки (avg %)",
    is_cancel: "Отмененные заказы",
    income_id: "Количество заказов",
}

const pageTitle = computed(() => metricNames[props.metricKey] || props.metricKey)

const rowsPerPage = 10
const props = defineProps({
  metricKey: { type: String, required: true },
})
const filtersStore = useFiltersStore()
const { allByMetric, loading } = useAggregatedOrders(filtersStore.filters, [props.metricKey])
const currentPageFirst = ref(0)
const tableRows = computed(() => allByMetric.value[props.metricKey] || [])
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

function onPageChange(first) {
  currentPageFirst.value = first
}
</script>

<style>
canvas {
  image-rendering: pixelated;
  display: block;
}
</style>
