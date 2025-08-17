<template>
    <div class="w-[90%] mx-auto mb-6 flex gap-3 justify-between flex-nowrap p-4 bg-gradient-to-r from-pink-500 to-purple-700 shadow-xl border-t border-amber-300">
        <!-- Артикул -->
        <div v-if="visibleFilters.includes('nm_id')" class="flex items-center w-64 min-w-[256px] h-10">
            <InputText
                v-model="filtersStore.filters.nm_id"
                placeholder="Артикул товара"
                class="w-44 h-full text-sm placeholder:text-gray-300 placeholder:font-normal placeholder:text-sm"
            />
            <button v-if="filtersStore.filters.nm_id" @click="resetFilter('nm_id')" class="w-8 h-8 ml-2 p-1 bg-gray-200 hover:bg-gray-300 rounded flex items-center justify-center" title="Сбросить артикул">
                ✕
            </button>
        </div>

        <!-- Регион -->
        <div v-if="visibleFilters.includes('region')" class="flex items-center w-64 min-w-[256px] h-10">
            <Dropdown
                v-model="filtersStore.filters.region"
                :options="regions"
                optionLabel="label"
                optionValue="value"
                placeholder="Регион продажи"
                class="w-50 h-full text-sm"
            />
            <button v-if="filtersStore.filters.region" @click="resetFilter('region')" class="w-8 h-8 ml-2 p-1 bg-gray-200 hover:bg-gray-300 rounded flex items-center justify-center" title="Сбросить регион">
                ✕
            </button>
        </div>

        <!-- Категория -->
        <div v-if="visibleFilters.includes('category')" class="flex items-center w-64 min-w-[256px] h-10">
            <Dropdown
                v-model="filtersStore.filters.category"
                :options="categories"
                optionLabel="label"
                optionValue="value"
                placeholder="Категория товара"
                class="w-50 h-full"
            />
            <button v-if="filtersStore.filters.category" @click="resetFilter('category')" class="w-8 h-8 ml-2 p-1 bg-gray-200 hover:bg-gray-300 rounded flex items-center justify-center" title="Сбросить категорию">
                ✕
            </button>
        </div>

        <!-- Бренд -->
        <div v-if="visibleFilters.includes('brand')" class="flex items-center w-64 min-w-[256px] h-10">
            <Dropdown
                v-model="filtersStore.filters.brand"
                :options="brands"
                optionLabel="label"
                optionValue="value"
                placeholder="Бренд"
                class="w-50 h-full"
            />
            <button v-if="filtersStore.filters.brand" @click="resetFilter('brand')" class="w-8 h-8 ml-2 p-1 bg-gray-200 hover:bg-gray-300 rounded flex items-center justify-center" title="Сбросить бренд">
                ✕
            </button>
        </div>

        <!-- Дата -->
        <div v-if="visibleFilters.includes('dateRange')" class="flex items-center w-64 min-w-[256px] h-10">
            <Calendar
                v-model="filtersStore.filters.dateRange"
                selectionMode="range"
                dateFormat="yy-mm-dd"
                placeholder="Период дат"
                class="w-50 h-full text-sm"
                appendTo="body"
            />
            <button v-if="filtersStore.filters.dateRange" @click="resetFilter('dateRange')" class="w-8 h-8 ml-2 p-1 bg-gray-200 hover:bg-gray-300 rounded flex items-center justify-center" title="Сбросить период">
                ✕
            </button>
        </div>
    </div>
</template>

<script setup>
import { watch } from "vue"
import { useFiltersStore } from '@/stores/filters'
import Dropdown from "primevue/dropdown"
import InputText from "primevue/inputtext"
import Calendar from "primevue/calendar"

const props = defineProps({
    regions: { type: Array, default: () => [] },
    categories: { type: Array, default: () => [] },
    brands: { type: Array, default: () => [] },
    visibleFilters: { type: Array, default: () => ["nm_id","region","category","brand","dateRange"] },
})

const emit = defineEmits(["update:filters", "reset-filter"])

const filtersStore = useFiltersStore()

watch(
    () => filtersStore.filters,
    (newVal) => emit("update:filters", newVal),
    { deep: true }
)

function resetFilter(key) {
    filtersStore.resetFilter(key)
    emit("reset-filter", key)
}
</script>

<style>
.placeholder-smaller::placeholder {
    font-size: 0.85rem;
    color: #999;
}

/* Для PrimeVue Dropdown надо попасть внутрь */
.p-dropdown .p-dropdown-label {
    font-size: 0.85rem !important;
    color: #555;
}
.p-dropdown-item {
    max-width: 250px;
    font-size: 14px;
}

.p-datepicker-dropdown {
    right: auto !important;
    left: auto !important;
    transform-origin: top left !important;
    margin-top: 6px !important;
    margin-right: 8px !important;
}

/* Общая ширина календаря */
.p-datepicker {
    max-width: 360px !important; /* или меньше */
    width: auto !important;
    box-sizing: border-box;
}

/* Уменьшаем ширину таблицы календаря */
.p-datepicker-calendar {
    table-layout: fixed !important;
    width: 100% !important;
    
}

.p-calendar .p-inputtext {
    font-size: 14px;
}

.p-datepicker-calendar td {
    padding: 0.3rem 0.4rem !important; /* чуть больше отступы */
    width: 14.28% !important; /* ровно 1/7 ширины */
    position: relative;
}

.p-datepicker-calendar td .p-highlight {
    border-radius: 50% !important;
    width: 2rem !important; /* увеличиваем размер кружка */
    height: 2rem !important;
    line-height: 2rem !important;
    margin: 0 auto !important; /* центрирование */
    display: block !important;
    overflow: visible !important;
    z-index: 2 !important;
    position: relative !important;
}
</style>
