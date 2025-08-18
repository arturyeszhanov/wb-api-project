<template>
    <HeaderInfo />

    <FilterPanel
        v-model:filters="filters"
        :regions="regions"
        :categories="categories"
        :brands="brands"
        @reset-filter="resetFilter"
    />

    <div class="w-[90%] mx-auto flex h-full gap-6 mb-12 mt-12">
        <!-- Левая часть -->
        <div class="w-1/3 bg-white shadow rounded-lg p-4">
            <h2 class="text-xl font-semibold mb-4">Артикул: {{ nmId }}</h2>

            <!-- Заглушка картинки -->
            <div
                class="w-full h-64 bg-gray-200 flex items-center justify-center mb-4 shadow-md rounded-md"
            >
                <span class="text-gray-600">[Image Placeholder]</span>
            </div>

            <!-- Информация о товаре -->
            <div>
                <div v-if="productInfo" class="p-4 bg-white shadow rounded">
                    <ul class="text-sm space-y-1">
                        <li>
                            <b>Артикул поставщика:</b>
                            {{ productInfo.supplier_article }}
                        </li>
                        <li><b>Размер:</b> {{ productInfo.tech_size }}</li>
                        <li><b>Штрихкод:</b> {{ productInfo.barcode }}</li>
                        <li><b>Бренд:</b> {{ productInfo.brand }}</li>
                        <li><b>Категория:</b> {{ productInfo.category }}</li>
                        <li><b>Предмет:</b> {{ productInfo.subject }}</li>
                        <li><b>Склад:</b> {{ productInfo.warehouse_name }}</li>
                        <li>
                            <b>Регион:</b>
                            <div v-if="productInfo.oblast?.length">
                                <div
                                    v-for="(o, index) in productInfo.oblast"
                                    :key="index"
                                >
                                    {{ o }}
                                </div>
                            </div>
                        </li>
                        <li><b>Цена:</b> {{ productInfo.total_price }}</li>
                        <li>
                            <b>Скидка (средн., %):</b>
                            {{ productInfo.discount_percent }}
                        </li>
                    </ul>
                </div>
                <div v-else class="p-4">Нет данных по артикулу</div>
            </div>
        </div>

        <!-- Правая часть -->
        <div class="w-2/3 bg-white shadow rounded-lg p-4">
            <div class="w-full flex">
                <!-- Левая колонка: фиксированные метрики -->
                <div class="w-1/4 flex-shrink-0 bg-gray-100 rounded-lg pb-2">
                    <h3
                        class="text-lg font-semibold mb-3 flex bg-gray-600 text-white p-1 pb-1 ps-3 rounded-tl-md"
                    >
                        Показатели
                        <ArrowTrendingUpIcon
                            class="w-10 ps-4 text-sm text-white"
                        />
                    </h3>
                    <ul class="space-y-3.5 text-md font-normal ps-3">
                        <li>Сумма продаж</li>
                        <li>Количество заказов</li>
                        <li>Отмененные заказы</li>
                        <li>Скидки (avg %)</li>
                    </ul>
                </div>

                <!-- Правая часть: даты + значения -->
                <div
                    ref="scrollContainer"
                    class="flex-1 overflow-x-auto scroll-container w-full rounded-tr-md"
                    style="scroll-behavior: smooth"
                >
                    <table class="min-w-max border-collapse border">
                        <thead>
                            <tr class="bg-gray-300">
                                <th
                                    v-for="d in dates"
                                    :key="d"
                                    class="px-3 py-2 font-normal text-sm whitespace-nowrap border-l border-gray-200 sticky top-0 z-0 bg-gray-600 text-white"
                                >
                                    {{ d }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="row in productMetrics"
                                :key="row.metric"
                                class="odd:bg-gray-100"
                            >
                                <td
                                    v-for="(val, i) in row.values"
                                    :key="i"
                                    class="px-3 py-2 whitespace-nowrap text-right border-l border-gray-200"
                                >
                                    {{ val }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="charts-grid pt-4">
                <div
                    v-for="(metric, index) in productMetrics"
                    :key="index"
                    class="chart-container"
                >
                    <h3 class="mb-3 font-normal text-gray-700">
                        {{ metric.metric }}
                    </h3>
                    <Chart
                        type="line"
                        :data="{
                            labels: dates,
                            datasets: [
                                {
                                    label: metric.metric,
                                    data: metric.values,
                                    borderColor: [
                                        '#8A2BE2',
                                        '#F2339D',
                                        '#40E0D0',
                                        '#4A5565',
                                    ][index],
                                    backgroundColor: [
                                        'rgba(138, 43, 226, 0.2)', // фиолетовый с прозрачностью
                                        'rgba(242, 51, 157, 0.2)', // розовый
                                        'rgba(64, 224, 208, 0.2)', // бирюзовый
                                        'rgba(74, 85, 101, 0.2)',  // серый
                                    ][index],
                                    fill: true,   // <--- включаем заливку
                                    tension: 0.3, // плавность линии
                                }
                                ,
                            ],
                        }"
                        :options="chartOptions"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Chart from "primevue/chart";
import HeaderInfo from "../components/HeaderInfo.vue";
import FilterPanel from "../components/FiltersPanel.vue";
import { ref, computed, watch } from "vue";
import { ArrowTrendingUpIcon } from "@heroicons/vue/24/outline";
import { useRoute } from "vue-router";
import { useProduct } from "@/composables/useProduct";
import { useFiltersStore } from '@/stores/filters'
import { storeToRefs } from 'pinia'

const filtersStore = useFiltersStore()

const route = useRoute();
const nmId = route.params.nmId;

import { onMounted, onBeforeUnmount } from "vue";

const scrollContainer = ref(null);

function handleWheel(e) {
    if (!scrollContainer.value) return;

    e.preventDefault();

    const step =
        scrollContainer.value.querySelector("table th")?.offsetWidth || 100;

    // scrollLeft анимировано за счет scroll-behavior: smooth
    if (e.deltaY > 0) {
        scrollContainer.value.scrollBy({ left: step, behavior: "smooth" });
    } else {
        scrollContainer.value.scrollBy({ left: -step, behavior: "smooth" });
    }
}

onMounted(() => {
    scrollContainer.value.addEventListener("wheel", handleWheel, {
        passive: false,
    });
});

onBeforeUnmount(() => {
    scrollContainer.value.removeEventListener("wheel", handleWheel);
});

const { filters } = storeToRefs(filtersStore) 

const props = defineProps({
    nmId: {
        type: String,
        required: true,
    },
});

const {
    regions,
    brands,
    categories,
    resetFilter,
    productInfo,
    productMetrics,
    dates,
} = useProduct(filters, nmId);

const chartOptions = {
    responsive: true,
    plugins: {
        legend: { display: false },
        title: { display: false },
    },
    scales: {
        y: { beginAtZero: true },
    },
};
</script>
<style>
.charts-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-top: 20px;
}

.chart-container {
    background: #fff;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.scroll-container {
    overflow-x: auto;
    display: flex;
    scroll-snap-type: x mandatory;
}

.scroll-container th,
.scroll-container td {
    scroll-snap-align: start;
    min-width: 132px;
}

.border {
    border-width: 0;
}
</style>
