import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useFiltersStore = defineStore('filters', () => {
    // состояние фильтров
    const filters = ref({
        nm_id: null,
        region: null,
        category: null,
        brand: null,
        dateRange: null,
    })

    // действия
    function updateFilter(key, value) {
        filters.value[key] = value
    }

    function resetFilter(key) {
        filters.value[key] = null
    }

    function resetAll() {
        Object.keys(filters.value).forEach(key => filters.value[key] = null)
    }

    return { filters, updateFilter, resetFilter, resetAll }
})
