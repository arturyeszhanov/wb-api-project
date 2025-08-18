import { ref, watch } from "vue"
import { storeToRefs } from "pinia"
import { useOrdersStore } from "@/stores/orders"
import { processItems } from "@/utils/dataProcessing_"

const emptyTopByMetric = () => ({
  total_price: [],
  discount_percent: [],
  is_cancel: [],
  income_id: [],
})

export function useAggregatedOrders(
  filters,
  metrics = ["total_price", "discount_percent", "is_cancel", "income_id"]
) {
  const aggregatedData = ref({})
  const topByMetric = ref(emptyTopByMetric())
  const allByMetric = ref(emptyTopByMetric()) // для полной метрики
  const loading = ref(false)

  const ordersStore = useOrdersStore()
  const { currentOrders, previousOrders, isLoading } = storeToRefs(ordersStore)

  const metricDirection = {
    total_price: true,
    discount_percent: true,
    is_cancel: false,
    income_id: true,
  }

  function calcChange(current, previous, positiveDirection = true) {
    const diff = current - previous
    let percentChange = previous === 0 ? (current === 0 ? 0 : 100) : (diff / previous) * 100
    if (!positiveDirection) percentChange = -percentChange

    let direction = "same"
    if (diff > 0) direction = "up"
    else if (diff < 0) direction = "down"

    return { diff, percentChange, direction }
  }

  function buildAggregations() {
    if (!currentOrders.value.length || !previousOrders.value.length) {
      aggregatedData.value = {}
      topByMetric.value = emptyTopByMetric()
      allByMetric.value = emptyTopByMetric()
      return
    }

    loading.value = true
    aggregatedData.value = {}
    topByMetric.value = emptyTopByMetric()
    allByMetric.value = emptyTopByMetric()

    metrics.forEach((metric) => {
      const processed = processItems(previousOrders.value, currentOrders.value, metric, { aggregate: true })

      // расчет агрегированных значений по метрике
      let currentValue = 0
      let previousValue = 0

      if (metric === "total_price") {
        currentValue = processed.reduce((sum, item) => sum + Number(item.current || 0), 0)
        previousValue = processed.reduce((sum, item) => sum + Number(item.previous || 0), 0)
      } else if (metric === "discount_percent") {
        currentValue =
          processed.reduce((sum, item) => sum + Number(item.current || 0), 0) / (processed.length || 1)
        previousValue =
          processed.reduce((sum, item) => sum + Number(item.previous || 0), 0) / (processed.length || 1)
      } else if (metric === "is_cancel") {
        currentValue = processed.reduce((sum, item) => sum + Number(item.current || 0), 0)
        previousValue = processed.reduce((sum, item) => sum + Number(item.previous || 0), 0)
      } else if (metric === "income_id") {
        const currentSet = new Set(processed.map((item) => item.current).filter(Boolean))
        const previousSet = new Set(processed.map((item) => item.previous).filter(Boolean))
        currentValue = currentSet.size
        previousValue = previousSet.size
      }

      const { diff, percentChange, direction } = calcChange(
        currentValue,
        previousValue,
        metricDirection[metric]
      )

      aggregatedData.value[metric] = {
        current: currentValue,
        previous: previousValue,
        diff,
        percentChange,
        direction,
      }

      // топ-5 по абсолютной разнице
      topByMetric.value[metric] = processed
        .map((item) => {
          const currentNum = Number(item.current || 0)
          const previousNum = Number(item.previous || 0)
          const { diff, percentChange, direction: itemDirection } = calcChange(
            currentNum,
            previousNum,
            metricDirection[metric]
          )
          return {
            nm_id: item.nm_id,
            current: currentNum,
            previous: previousNum,
            diff,
            percentChange,
            direction: itemDirection,
          }
        })
        .sort((a, b) => Math.abs(b.diff) - Math.abs(a.diff))
        .slice(0, 5)

      // полная метрика по убыванию diff для детализации
      allByMetric.value[metric] = processed
        .map((item) => {
          const currentNum = Number(item.current || 0)
          const previousNum = Number(item.previous || 0)
          const { diff, percentChange, direction: itemDirection } = calcChange(
            currentNum,
            previousNum,
            metricDirection[metric]
          )
          return {
            nm_id: item.nm_id,
            region: item.region,
            category: item.category,
            brand: item.brand,
            current: currentNum,
            previous: previousNum,
            diff,
            percentChange,
            direction: itemDirection,
          }
        })
        .sort((a, b) => b.diff - a.diff) // сортировка по убыванию diff
    })

    loading.value = false
  }

  watch(
    [currentOrders, previousOrders, filters],
    () => {
      buildAggregations()
    },
    { deep: true, immediate: true }
  )

  return {
    aggregatedData,
    topByMetric,
    allByMetric, 
    loading: isLoading,
  }
}
