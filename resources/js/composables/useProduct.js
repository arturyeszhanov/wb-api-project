import { ref, watch, computed } from "vue";
import { fetchOrders } from "@/services/ordersService";
import { buildDateRange } from "@/utils/dateUtils";

export function useProduct(filters, nmId) {
  const productInfo = ref(null);
  const productOrders = ref([]);

  function normalizeDate(dateTime) {
    const date = new Date(dateTime);
    return date.toISOString().split("T")[0]; // YYYY-MM-DD
  }

  async function loadProduct(dateRange) {
    if (!dateRange?.current?.from || !dateRange?.current?.to) {
      productInfo.value = null;
      productOrders.value = [];
      return;
    }

    const currentData = await fetchOrders(
      dateRange.current.from,
      dateRange.current.to
    );

    // фильтруем по nmId и нормализуем даты
    const filtered = currentData
      .filter(o => String(o.nm_id) === String(nmId))
      .map(o => ({ ...o, date: normalizeDate(o.date) }));

      if (filtered.length > 0) {
        productOrders.value = filtered;
      
        const first = filtered[0];
      
        const brands = [...new Set(filtered.map(o => o.brand).filter(Boolean))];
        const categories = [...new Set(filtered.map(o => o.category).filter(Boolean))];
        const oblasts = [...new Set(filtered.map(o => o.oblast).filter(Boolean))];
      
        // собираем все цены и определяем минимальную и максимальную
        const prices = filtered.map(o => Number(o.total_price || 0));
        const minPrice = Math.min(...prices);
        const maxPrice = Math.max(...prices);
        const priceRange = minPrice === maxPrice
        ? `${minPrice.toFixed(2)}`
        : `Мин: ${minPrice.toFixed(2)}  –  Макс: ${maxPrice.toFixed(2)}`;
      
      
        productInfo.value = {
          nm_id: first.nm_id,
          supplier_article: first.supplier_article,
          tech_size: first.tech_size,
          barcode: first.barcode,
          brand: brands.join(", "),
          category: categories.join(", "),
          subject: first.subject,
          warehouse_name: first.warehouse_name,
          oblast: oblasts,
          total_price: priceRange,
          discount_percent: first.discount_percent,
          is_cancel: first.is_cancel,
        };
      }
      
      
  }

  const dates = computed(() => {
    const set = new Set(productOrders.value.map(o => o.date));
    return Array.from(set).sort();
  });

  const productMetrics = computed(() => {
    if (!productOrders.value.length) return [];

    return [
      {
        metric: "Сумма продаж",
        values: dates.value.map(d => {
          const total = productOrders.value
            .filter(o => o.date === d)
            .reduce((sum, o) => sum + Number(o.total_price || 0), 0);
          return Math.round(total * 100) / 100;
        }),
      },
      {
        metric: "Количество заказов",
        values: dates.value.map(d =>
          productOrders.value.filter(o => o.date === d).length
        ),
      },
      {
        metric: "Отмененные заказы",
        values: dates.value.map(d =>
          productOrders.value.filter(o => o.date === d && o.is_cancel).length
        ),
      },
      {
        metric: "Скидки (avg %)",
        values: dates.value.map(d => {
          const orders = productOrders.value.filter(o => o.date === d);
          return orders.length
            ? Math.round(
                orders.reduce((s, o) => s + (o.discount_percent || 0), 0) / orders.length
              )
            : 0;
        }),
      },
    ];
  });

  watch(
    () => filters.value.dateRange,
    async (newDates) => {
      if (Array.isArray(newDates) && newDates.length === 2) {
        const dateRange = buildDateRange(newDates);
        if (dateRange?.current) {
          await loadProduct({ current: dateRange.current });
        }
      }
    },
    { deep: false, immediate: true }
  );

  return {
    productInfo,
    productOrders,
    productMetrics,
    dates,
    loadProduct,
  };
}
