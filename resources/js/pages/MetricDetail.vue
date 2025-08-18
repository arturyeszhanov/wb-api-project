<template>
  <HeaderInfo />

  <FilterPanel
  :regions="regions"
  :categories="categories"
  :brands="brands"
/>
    <div class="w-[90%] mx-auto flex justify-start ps-60 text-gray-900 mt-10">
    <h2 class="text-2xl md:text-2xl font-bold text-gray-800">
        {{ pageTitle }}
    </h2>
    </div>
    <div class="w-[90%] mx-auto flex flex-col md:flex-row gap-3 mb-12 mt-3">
  <div class="flex-1 bg-white rounded-md shadow-xl p-3">
  <DataChart :data="filteredRows.slice(currentPageFirst, currentPageFirst + rowsPerPage)" />
  </div>
  <div class="flex-1 bg-white rounded-md shadow-xl p-3">
  <DataTable :data="filteredRows" :rows="rowsPerPage" @page-change="onPageChange" />
  </div>
</div>

</template>

<script setup>
import { ref,watch, computed } from "vue"
import HeaderInfo from "../components/HeaderInfo.vue"
import FilterPanel from "../components/FiltersPanel.vue"
import DataChart from "../components/DataChart.vue"
import DataTable from "../components/DataTable.vue"
import { useOrdersStore } from "@/stores/orders";
import { useFiltersStore } from "@/stores/filters"
import { useAggregatedOrders } from "@/composables/useAggregatedOrders"

const metricNames = {
    total_price: "Сумма продаж",
    discount_percent: "Скидки (avg %)",
    is_cancel: "Отмененные заказы",
    income_id: "Количество заказов",
}
const ordersStore = useOrdersStore();
const filtersStore = useFiltersStore()
watch(
    () => filtersStore.filters.dateRange,
    (newRange) => {
        ordersStore.fetchOrdersForRange(newRange);
    },
    { deep: false, immediate: true }
);

const pageTitle = computed(() => metricNames[props.metricKey] || props.metricKey)

const rowsPerPage = 10
const props = defineProps({
  metricKey: { type: String, required: true },
})

const { allByMetric, loading } = useAggregatedOrders(filtersStore.filters, [props.metricKey])
const currentPageFirst = ref(0)
const tableRows = computed(() => allByMetric.value[props.metricKey] || [])

function onPageChange(first) {
  currentPageFirst.value = first
}


const regions = computed(() =>
  [...new Set(tableRows.value.map(order => order.region))]
    .filter(Boolean)
    .sort((a, b) => a.localeCompare(b)) // сортировка по алфавиту
    .map(r => ({ label: r, value: r }))
)


const categories = computed(() =>
  [...new Set(tableRows.value.map(order => order.category))]
    .filter(Boolean)
    .sort((a, b) => a.localeCompare(b))
    .map(c => ({ label: c, value: c }))
)

const brands = computed(() =>
  [...new Set(tableRows.value.map(order => order.brand))]
    .filter(Boolean)
    .sort((a, b) => a.localeCompare(b))
    .map(b => ({ label: b, value: b }))
)


const filteredRows = computed(() => {
  return tableRows.value.filter(order => {
    const { region, category, brand, nm_id } = filtersStore.filters

    if (region && order.region !== region) return false
    if (category && order.category !== category) return false
    if (brand && order.brand !== brand) return false

    if (nm_id) {
      const search = nm_id.toString().trim()
      const value = (order.nm_id ?? "").toString()
      if (!value.includes(search)) return false
    }

    return true
  })
})


</script>

<style>
canvas {
  image-rendering: pixelated;
  display: block;
}
</style>
