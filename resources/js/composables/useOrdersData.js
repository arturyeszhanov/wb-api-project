import { ref, computed, watch, unref } from "vue"
import { storeToRefs } from "pinia"
import { useOrdersStore } from "@/stores/orders"

export function useOrdersData(filters, rowsPerPage = 10) {
  const ordersStore = useOrdersStore()
  const { currentOrders, previousOrders } = storeToRefs(ordersStore)

  const currentPageFirst = ref(0)

  const allData = computed(() => [...previousOrders.value, ...currentOrders.value])
  const tableData = computed(() => currentOrders.value)

  const filteredData = computed(() => {
    const f = unref(filters)   // <-- фикс: вытаскиваем реальное значение
    if (!f) return tableData.value

    const range = f.dateRange
    return tableData.value.filter((item) => {
      const matchNmId = !f.nm_id || String(item.nm_id).includes(f.nm_id)
      const matchRegion = !f.region || item.region === f.region
      const matchBrand = !f.brand || item.brand === f.brand
      const matchCategory = !f.category || item.category === f.category

      let matchDate = true
      if (range?.current?.from && range?.current?.to) {
        const itemDate = new Date(item.date)
        const from = new Date(range.current.from)
        const to = new Date(range.current.to)
        matchDate = itemDate >= from && itemDate <= to
      }

      return matchNmId && matchRegion && matchBrand && matchCategory && matchDate
    })
  })

  const pagedData = computed(() => {
    return filteredData.value.slice(currentPageFirst.value, currentPageFirst.value + rowsPerPage)
  })

  const regions = computed(() =>
    [...new Set(allData.value.map((i) => i.region).filter(Boolean))].map((r) => ({
      label: r,
      value: r,
    }))
  )
  const brands = computed(() =>
    [...new Set(allData.value.map((i) => i.brand).filter(Boolean))].map((b) => ({
      label: b,
      value: b,
    }))
  )
  const categories = computed(() =>
    [...new Set(allData.value.map((i) => i.category).filter(Boolean))].map((c) => ({
      label: c,
      value: c,
    }))
  )

  watch(
    () => unref(filters),
    () => {
      currentPageFirst.value = 0
    },
    { deep: true }
  )

  return {
    tableData,
    allData,
    regions,
    brands,
    categories,
    filteredData,
    pagedData,
    currentPageFirst,
  }
}
