<template>
    <HeaderInfo />
    <div class="flex justify-center items-center">
        <FilterPanel
            :disabled-filters="['nm_id', 'region', 'category', 'brand']"
        />
    </div>

    <div class="w-[90%] mx-auto grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 mb-2">
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
import DataChart from "../components/DataChart_.vue";
import DataTable from "../components/DataTable.vue";

import { useOrdersStore } from "@/stores/orders";
import { useFiltersStore } from "@/stores/filters";
import { useAggregatedOrders } from "../composables/useAggregatedOrders";
import { useChartAggregator } from "../composables/useChartAggregator";
import { useRouter } from "vue-router";
const router = useRouter();

function metricRouteName(metric) {
    // Преобразуем ключ метрики в PascalCase для роутера
    return metric
        .split("_")
        .map((word) => word[0].toUpperCase() + word.slice(1))
        .join("");
}

// Метрики и их отображаемые названия
const metrics = ["total_price", "discount_percent", "is_cancel", "income_id"];
const metricLabels = {
    total_price: "Сумма продаж",
    discount_percent: "Скидки (средний %)",
    is_cancel: "Отмененные заказы",
    income_id: "Количество заказов",
};

const rowsPerPage = ref(10);

const ordersStore = useOrdersStore();
const filtersStore = useFiltersStore();

// следим за изменением диапазона дат и загружаем заказы
watch(
    () => filtersStore.filters.dateRange,
    (newRange) => {
        ordersStore.fetchOrdersForRange(newRange);
    },
    { deep: false, immediate: true }
);

// хуки теперь сами берут данные из ordersStore
const { chartData } = useChartAggregator(metrics);
const { topByMetric } = useAggregatedOrders(filtersStore.filters, metrics);
</script>
