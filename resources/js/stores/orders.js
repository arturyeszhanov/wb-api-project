import { defineStore } from 'pinia'
import { ref } from 'vue'
import { fetchOrders } from '@/services/ordersService'
import { buildDateRange } from '@/utils/dateUtils'

export const useOrdersStore = defineStore('orders', () => {
  const currentOrders = ref([])
  const previousOrders = ref([])
  const isLoading = ref(false)
  const error = ref(null)

  /**
   * Загружаем заказы сразу за оба периода (текущий + предыдущий)
   * @param {Array} dateRange - [from, to]
   */
  async function fetchOrdersForRange(dateRange) {
    if (!Array.isArray(dateRange) || dateRange.length !== 2) {
      currentOrders.value = []
      previousOrders.value = []
      return
    }

    const builtRange = buildDateRange(dateRange)
    if (!builtRange) {
      currentOrders.value = []
      previousOrders.value = []
      return
    }

    try {
      isLoading.value = true
      error.value = null

      const [curr, prev] = await Promise.all([
        fetchOrders(builtRange.current.from, builtRange.current.to),
        fetchOrders(builtRange.previous.from, builtRange.previous.to),
      ])

      currentOrders.value = curr || []
      previousOrders.value = prev || []
    } catch (err) {
      error.value = err.message || 'Ошибка при загрузке заказов'
      currentOrders.value = []
      previousOrders.value = []
    } finally {
      isLoading.value = false
    }
  }

  return {
    currentOrders,
    previousOrders,
    isLoading,
    error,
    fetchOrdersForRange,
  }
})
