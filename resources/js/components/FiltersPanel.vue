<template>
    <div
        class="w-[90%] mx-auto mb-6 flex gap-3 justify-between flex-wrap p-5 bg-gradient-to-r from-pink-500 to-purple-700 shadow-xl border-t-2 border-amber-300"
    >
        <!-- Артикул -->
        <div class="flex items-center w-64 min-w-[256px] h-10">
            <InputText
                v-model="filtersStore.filters.nm_id"
                placeholder="Артикул товара"
                class="w-44 h-full text-sm placeholder:text-gray-300 placeholder:font-normal placeholder:text-sm"
                :disabled="disabledFilters.includes('nm_id')"
            />
            <button
                v-if="filtersStore.filters.nm_id"
                @click="resetFilter('nm_id')"
                class="w-8 h-8 ml-2 p-1 bg-amber-300 hover:bg-amber-400 rounded flex items-center justify-center cursor-pointer"
                title="Сбросить артикул"
            >
                ✕
            </button>
        </div>

        <!-- Регион -->
        <div class="flex items-center w-64 min-w-[256px] h-10">
            <Dropdown
                v-model="filtersStore.filters.region"
                :options="regions"
                optionLabel="label"
                optionValue="value"
                placeholder="Регион продажи"
                class="w-50 h-full text-sm"
                :disabled="disabledFilters.includes('region')"
            />
            <button
                v-if="filtersStore.filters.region"
                @click="resetFilter('region')"
                class="w-8 h-8 ml-2 p-1 bg-amber-300 hover:bg-amber-400 rounded flex items-center justify-center cursor-pointer"
                title="Сбросить регион"
            >
                ✕
            </button>
        </div>

        <!-- Категория -->
        <div class="flex items-center w-64 min-w-[256px] h-10">
            <Dropdown
                v-model="filtersStore.filters.category"
                :options="categories"
                optionLabel="label"
                optionValue="value"
                placeholder="Категория товара"
                class="w-50 h-full"
                :disabled="disabledFilters.includes('category')"
            />
            <button
                v-if="filtersStore.filters.category"
                @click="resetFilter('category')"
                class="w-8 h-8 ml-2 p-1 bg-amber-300 hover:bg-amber-400 rounded flex items-center justify-center cursor-pointer"
                title="Сбросить категорию"
            >
                ✕
            </button>
        </div>

        <!-- Бренд -->
        <div class="flex items-center w-64 min-w-[256px] h-10">
            <Dropdown
                v-model="filtersStore.filters.brand"
                :options="brands"
                optionLabel="label"
                optionValue="value"
                placeholder="Бренд"
                class="w-50 h-full"
                :disabled="disabledFilters.includes('brand')"
            />
            <button
                v-if="filtersStore.filters.brand"
                @click="resetFilter('brand')"
                class="w-8 h-8 ml-2 p-1 bg-amber-300 hover:bg-amber-400 rounded flex items-center justify-center cursor-pointer"
                title="Сбросить бренд"
            >
                ✕
            </button>
        </div>

        <!-- Дата -->
        <div class="flex items-center w-64 min-w-[256px] h-10">
            <Calendar
                v-model="filtersStore.filters.dateRange"
                selectionMode="range"
                dateFormat="yy-mm-dd"
                placeholder="Диапазон дат"
                class="w-50 h-full text-sm"
                appendTo="body"
                :disabled="disabledFilters.includes('dateRange')"
            />
            <button
                v-if="filtersStore.filters.dateRange"
                @click="resetFilter('dateRange')"
                class="w-8 h-8 ml-2 p-1 bg-amber-300 hover:bg-amber-400 rounded flex items-center justify-center cursor-pointer"
                title="Сбросить период"
            >
                ✕
            </button>
        </div>
    </div>
</template>

<script setup>
import { watch } from "vue";
import { useFiltersStore } from "@/stores/filters";
import Dropdown from "primevue/dropdown";
import InputText from "primevue/inputtext";
import Calendar from "primevue/calendar";

const props = defineProps({
    regions: { type: Array, default: () => [] },
    categories: { type: Array, default: () => [] },
    brands: { type: Array, default: () => [] },
    disabledFilters: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(["update:filters", "reset-filter"]);

const filtersStore = useFiltersStore();

watch(
    () => filtersStore.filters,
    (newVal) => emit("update:filters", newVal),
    { deep: true }
);

function resetFilter(key) {
    filtersStore.resetFilter(key);
    emit("reset-filter", key);
}
</script>

<style>
.p-inputtext {
    font-size: 14px;
    color: #333;
    padding-top: 10px;
    
}

.p-inputtext::placeholder {
    font-size: 14px;
    color: #666;
}

.p-dropdown-item {
    max-width: 250px;
    font-size: 13px;
    color: #333;
}

.p-dropdown:not(.p-disabled).p-focus {
    box-shadow: 0 0 0 0.1rem #ffd131;
    border-color: #ffd131;
}

.p-calendar .p-inputtext {
    font-size: 13px;
    color: #666;
    padding-top: 15px;
}

.p-inputtext:enabled:focus {
    box-shadow: 0 0 0 0.1rem #ffd131;
    border-color: #ffd131;
}

/* Снижаем размер выпадающего календаря */
.p-datepicker {
    font-size: 0.75rem; /* уменьшает шрифт */
    width: 20rem;       /* ширина календаря */
}

.p-datepicker td, 
.p-datepicker th {
    padding: 0.10rem;   /* уменьшаем отступы */
}
</style>
