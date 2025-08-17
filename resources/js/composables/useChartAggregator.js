import { ref, watch, computed } from "vue"
import { storeToRefs } from "pinia"
import { useOrdersStore } from "@/stores/orders"

export function useChartAggregator(metrics = ["total_price"]) {
  const rawData = ref([]) // массив объектов { date, previous: {}, current: {} }

  const ordersStore = useOrdersStore()
  const { currentOrders, previousOrders } = storeToRefs(ordersStore)

  // Агрегация по метрике
  const aggregateByMetric = (data, metric) => {
    const map = {}
    const countMap = {} // для average (discount_percent)

    data.forEach(item => {
      const date = item.date.split(" ")[0]
      if (!map[date]) map[date] = 0
      if (!countMap[date]) countMap[date] = 0

      const value = item[metric]

      if (metric === "discount_percent") {
        map[date] += Number(value) || 0
        countMap[date] += 1
      } else if (metric === "is_cancel") {
        map[date] += value ? 1 : 0
      } else if (metric === "income_id") {
        map[date] += 1 // считаем количество заказов
      } else {
        map[date] += Number(value) || 0
      }
    })

    if (metric === "discount_percent") {
      Object.keys(map).forEach(date => {
        map[date] = Math.round(map[date] / countMap[date]) // среднее
      })
    }

    return map
  }

  // Преобразуем rawData в формат Chart.js для конкретной метрики
  const getChartData = (metric) => {
    if (!rawData.value.length) return { labels: [], datasets: [] }

    const dates = rawData.value.map(d => d.date)
    return {
      labels: dates,
      datasets: [
        {
          label: "Предыдущий период",
          data: rawData.value.map(d => d.previous[metric] || 0),
          borderColor: "#FF6384",
          backgroundColor: "rgba(255, 99, 132, 0.2)",
          fill: true,
          tension: 0.3,
        },
        {
          label: "Текущий период",
          data: rawData.value.map(d => d.current[metric] || 0),
          borderColor: "#42A5F5",
          backgroundColor: "rgba(66, 165, 245, 0.2)",
          fill: true,
          tension: 0.3,
        },
      ],
    }
  }

  const chartData = computed(() => {
    const result = {}
    metrics.forEach(metric => {
      result[metric] = getChartData(metric)
    })
    return result
  })

  function buildChartData() {
    if (!currentOrders.value.length && !previousOrders.value.length) {
      rawData.value = []
      return
    }

    const prevMap = {}
    const currMap = {}

    metrics.forEach(metric => {
      prevMap[metric] = aggregateByMetric(previousOrders.value, metric)
      currMap[metric] = aggregateByMetric(currentOrders.value, metric)
    })

    const allDates = Array.from(
      new Set([
        ...Object.values(prevMap).flatMap(m => Object.keys(m)),
        ...Object.values(currMap).flatMap(m => Object.keys(m)),
      ])
    ).sort()

    rawData.value = allDates.map(date => {
      const previous = {}
      const current = {}
      metrics.forEach(metric => {
        previous[metric] = prevMap[metric][date] || 0
        current[metric] = currMap[metric][date] || 0
      })
      return { date, previous, current }
    })
  }

  function clearChartData() {
    rawData.value = []
  }

  // следим за изменением заказов в сторе
  watch(
    [currentOrders, previousOrders],
    () => {
      if (currentOrders.value.length || previousOrders.value.length) {
        buildChartData()
      } else {
        clearChartData()
      }
    },
    { deep: true, immediate: true }
  )

  return {
    chartData,
    clearChartData,
  }
}
