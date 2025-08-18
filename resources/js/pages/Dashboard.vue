<template>
    <HeaderInfo />
    <div class="flex justify-center items-center">
        <FilterPanel
            :disabled-filters="['nm_id', 'region', 'category', 'brand']"
        />
    </div>

    <div class="w-[90%] mx-auto grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 mb-12">
        <div
            v-for="metric in metrics"
            :key="metric"
            class="bg-white rounded-md shadow-xl p-4"
        >
            <h3 class="text-lg font-semibold mb-2">
                {{ metricLabels[metric] }}
            </h3>
            <div>
                <RouterLink
                    :to="{ name: metricRouteName(metric) }"
                    class="block cursor-pointer"
                >
                    <DataChart
                        :data="chartData[metric]"
                        :chart-type="chartTypes[metric]"
                        class="h-full ps-1"
                        style="height: 300px"
                    />
                </RouterLink>
            </div>
            <DataTable
                :data="topByMetric[metric]"
                :rows="rowsPerPage"
                :paginator="false"
            />
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import HeaderInfo from "../components/HeaderInfo.vue";
import FilterPanel from "../components/FiltersPanel.vue";
import DataChart from "../components/ChartDashboard.vue";
import DataTable from "../components/DataTable.vue";
import { useOrdersStore } from "@/stores/orders";
import { useFiltersStore } from "@/stores/filters";
import { useAggregatedOrders } from "../composables/useAggregatedOrders";
import { useChartAggregator } from "../composables/useChartAggregator";

function metricRouteName(metric) {
    return metric
        .split("_")
        .map((word) => word[0].toUpperCase() + word.slice(1))
        .join("");
}

const chartTypes = {
  total_price: "line",
  discount_percent: "bar",
  is_cancel: "line",
  income_id: "bar",
};

const metrics = ["total_price", "income_id", "is_cancel", "discount_percent" ];
const metricLabels = {
    total_price: "Сумма продаж",
    discount_percent: "Скидки (avg %)",
    is_cancel: "Отмененные заказы",
    income_id: "Количество заказов",
};

const rowsPerPage = ref(10);
const ordersStore = useOrdersStore();
const filtersStore = useFiltersStore();

watch(
    () => filtersStore.filters.dateRange,
    (newRange) => {
        ordersStore.fetchOrdersForRange(newRange);
    },
    { deep: false, immediate: true }
);

const { chartData } = useChartAggregator(metrics);
const { topByMetric } = useAggregatedOrders(filtersStore.filters, metrics);
</script>
