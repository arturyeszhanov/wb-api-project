<template>
	<HeaderInfo/>
	<!-- Фильтры -->
	<FilterPanel
		v-model:filters="filters"
		:regions="regions"
		:categories="categories"
		:brands="brands"
		@reset-filter="resetFilter"
	/>

<div class="w-[90%] mx-auto grid grid-cols-1 md:grid-cols-2 gap-6 rounded-md mb-12 mt-12">
<div v-for="(metric, index) in metrics" :key="metric.key" class="bg-white p-4 rounded-md shadow-xl flex flex-col gap-4">
  <DataChart
    :data="metric.dataForChart"
    @click="goToMetricPage(metric.key)"
    class="cursor-pointer"
  />
  <DataTable
    :data="metric.topData"
	:paginator="false"
  />
</div>

</div>

    
</template>

<script setup>
import { ref, computed, watch } from "vue";
import axios from "axios";
import HeaderInfo from "../components/HeaderInfo.vue";
import FilterPanel from "../components/FiltersPanel.vue";
import DataChart from "../components/DataChart_.vue";
import DataTable from "../components/DataTable.vue";
import { useOrdersData } from "@/composables/useOrdersData";




// Фильтры
const filters = ref({
  nm_id: null,
  region: null,
  category: null,
  brand: null,
  dateRange: null,
});

const regions = ref([]);
const brands = ref([]);
const categories = ref([]);

// Данные
const tableData = ref([]);
const allData = ref([]);
const currentPageFirst = ref(0);
const rowsPerPage = 10;

// Форматирование даты
function formatDate(date) {
  const yyyy = date.getFullYear();
  const mm = String(date.getMonth() + 1).padStart(2, "0");
  const dd = String(date.getDate()).padStart(2, "0");
  return `${yyyy}-${mm}-${dd}`;
}

// Фильтрация данных
const filteredData = computed(() => {
  const range = filters.value.dateRange;

  return tableData.value.filter((item) => {
    const matchNmId = !filters.value.nm_id || String(item.nm_id).includes(filters.value.nm_id);
    const matchRegion = !filters.value.region || item.region === filters.value.region;
    const matchBrand = !filters.value.brand || item.brand === filters.value.brand;
    const matchCategory = !filters.value.category || item.category === filters.value.category;

    let matchDate = true;
    if (range?.current?.from && range?.current?.to) {
      const itemDate = new Date(item.date);
      const from = new Date(range.current.from);
      const to = new Date(range.current.to);
      matchDate = itemDate >= from && itemDate <= to;
    }

    return matchNmId && matchRegion && matchBrand && matchCategory && matchDate;
  });
});

// Пагинация
const pagedData = computed(() => {
  return filteredData.value.slice(
    currentPageFirst.value,
    currentPageFirst.value + rowsPerPage
  );
});




// Сброс фильтров
function resetFilter(key) {
  filters.value[key] = null;
  currentPageFirst.value = 0;
}

// Обработка пагинации
function onPageChange(first) {
  currentPageFirst.value = first;
}

watch(filters, () => {
  currentPageFirst.value = 0;
}, { deep: true });


// Построение диапазона дат
function buildDateRange(selectedDates) {
  if (!selectedDates || selectedDates.length < 2) return null;

  const [start, end] = selectedDates;
  if (!(start instanceof Date) || isNaN(start) || !(end instanceof Date) || isNaN(end)) {
    return null;
  }

  const durationMs = end.getTime() - start.getTime();
  const prevStart = new Date(start.getTime() - durationMs - 86400000);
  const prevEnd = new Date(end.getTime() - durationMs - 86400000);

  return {
    current: { from: formatDate(start), to: formatDate(end) },
    previous: { from: formatDate(prevStart), to: formatDate(prevEnd) },
  };
}











// Подсчёт количества продаж (число заказов по nm_id)
function countSales(data) {
  const map = {};
  data.forEach(item => {
    const id = item.nm_id;
    if (!map[id]) {
      map[id] = 0;
    }
    map[id]++;
  });
  return map;
}

// Подсчёт количества отмен (is_cancel === true)
function countCanceled(data) {
  const map = {};
  data.forEach(item => {
    if (item.is_cancel) {
      const id = item.nm_id;
      if (!map[id]) {
        map[id] = 0;
      }
      map[id]++;
    }
  });
  return map;
}

// Суммирование total_price
function sumMetric(data, field) {
  const map = {};
  data.forEach(item => {
    const id = item.nm_id;
    const val = parseFloat(item[field]) || 0;
    if (!map[id]) {
      map[id] = 0;
    }
    map[id] += val;
  });
  return map;
}

// Средний discount_percent
function avgDiscount(data) {
  const map = {};
  const counts = {};
  data.forEach(item => {
    const id = item.nm_id;
    const val = parseFloat(item.discount_percent) || 0;
    if (!map[id]) {
      map[id] = 0;
      counts[id] = 0;
    }
    map[id] += val;
    counts[id]++;
  });
  Object.keys(map).forEach(id => {
    map[id] = counts[id] > 0 ? map[id] / counts[id] : 0;
  });
  return map;
}




function buildTopByMetric(previousData, currentData, metricFn) {
  const prevMap = metricFn(previousData);
  const currMap = metricFn(currentData);

  const allIds = new Set([...Object.keys(prevMap), ...Object.keys(currMap)]);

  const result = Array.from(allIds).map(id => {
    const prev = prevMap[id] || 0;
    const curr = currMap[id] || 0;
    const change = prev === 0 ? (curr === 0 ? 0 : 100) : ((curr - prev) / prev) * 100;
    return {
      nm_id: id,
      previous: prev,
      current: curr,
      percentChange: change,
    };
  });

  // Сортируем по абсолютному значению % изменения, сверху самые большие
  result.sort((a, b) => Math.abs(b.percentChange) - Math.abs(a.percentChange));

  // Возвращаем топ, например, топ 10
  return result.slice(0, 5);
}


const totalPriceTop = computed(() => buildTopByMetric(previousDataRaw.value, currentDataRaw.value, data => sumMetric(data, 'total_price')));
const canceledTop = computed(() => buildTopByMetric(previousDataRaw.value, currentDataRaw.value, countCanceled));
const discountTop = computed(() => buildTopByMetric(previousDataRaw.value, currentDataRaw.value, avgDiscount));
const salesCountTop = computed(() => buildTopByMetric(previousDataRaw.value, currentDataRaw.value, countSales));


const previousDataRaw = ref([]);
const currentDataRaw = ref([]);










// === Агрегаторы для трендов ===
function buildTrend(previousData, currentData, field, type = 'sum', topN = 5) {
  const resultMap = {};
  const allData = [...previousData.map(d => ({...d, period: 'prev'})), 
                   ...currentData.map(d => ({...d, period: 'curr'}))];

  allData.forEach(item => {
    const id = item.nm_id;
    if (!resultMap[id]) resultMap[id] = {prev: 0, curr: 0};

    let value = 0;
    if (type === 'sum') value = parseFloat(item[field]) || 0;
    if (type === 'count') value = 1;
    if (type === 'avg') value = parseFloat(item[field]) || 0;

    resultMap[id][item.period] += value;
  });

  const result = Object.entries(resultMap).map(([id, {prev, curr}]) => {
    const change = prev === 0 ? (curr === 0 ? 0 : 100) : ((curr - prev) / prev) * 100;
    return {nm_id: id, previous: prev, current: curr, percentChange: change};
  });

  result.sort((a, b) => Math.abs(b.percentChange) - Math.abs(a.percentChange));
  return result.slice(0, topN);
}





// Загрузка данных
async function fetchData(dateRange) {
  try {
    if (!dateRange?.current?.from || !dateRange?.current?.to) {
      tableData.value = [];
      return;
    }

    const currentStart = new Date(dateRange.current.from);
    const currentEnd = new Date(dateRange.current.to);
    const durationMs = currentEnd.getTime() - currentStart.getTime();

    let prevStart, prevEnd;
    if (dateRange.previous?.from && dateRange.previous?.to) {
      prevStart = new Date(dateRange.previous.from);
      prevEnd = new Date(dateRange.previous.to);
    } else {
      prevStart = new Date(currentStart.getTime() - durationMs - 86400000);
      prevEnd = new Date(currentEnd.getTime() - durationMs - 86400000);
    }

    const [current, previous] = await Promise.all([
      axios.get("http://109.73.206.144:6969/api/orders", {
        params: {
          dateFrom: dateRange.current.from,
          dateTo: dateRange.current.to,
          page: 1,
          limit: 500,
          key: "E6kUTYrYwZq2tN4QEtyzsbEBk3ie",
        },
      }),
      axios.get("http://109.73.206.144:6969/api/orders", {
        params: {
          dateFrom: dateRange.previous.from,
          dateTo: dateRange.previous.to,
          page: 1,
          limit: 500,
          key: "E6kUTYrYwZq2tN4QEtyzsbEBk3ie",
        },
      }),
    ]);

    const previousData = previous.data.data;
    const currentData = current.data.data;

    allData.value = [...previousData, ...currentData];

    previousDataRaw.value = previousData;
    currentDataRaw.value = currentData;

    // ====== ТАБЛИЦА (как раньше) ======
    totalPriceData.value = buildTopByMetric(previousData, currentData, data => sumMetric(data, 'total_price'));
    ordersCountData.value = buildTopByMetric(previousData, currentData, countSales);
    canceledOrdersData.value = buildTopByMetric(previousData, currentData, countCanceled);
    discountData.value = buildTopByMetric(previousData, currentData, avgDiscount);

    // ====== ГРАФИК (тренд по дням) ======
    totalPriceTrend.value = buildTrendByMetric(allData.value, data => {
      console.log('totalPrice data for day:', data);
      return sumTotalPrice(data);
    });
    
    ordersCountTrend.value = buildTrendByMetric(allData.value, data => {
      console.log('ordersCount data for day:', data);
      return countOrders(data);
    });
    
    canceledOrdersTrend.value = buildTrendByMetric(allData.value, data => {
      console.log('canceledOrders data for day:', data);
      return countCanceledOrders(data);
    });
    
    discountTrend.value = buildTrendByMetric(allData.value, data => {
      console.log('discount data for day:', data);
      return avgDiscountPerDay(data);
    });
     


  } catch (err) {
    console.error("Ошибка загрузки данных", err);
  }
}

// Универсальная функция тренда
function buildTrendByMetric(data, metricFn) {
  const grouped = {};
  data.forEach(item => {
    const date = item.date?.slice(0, 10) || item.created_at?.slice(0, 10);
    if (!grouped[date]) grouped[date] = [];
    grouped[date].push(item);
  });

  return Object.keys(grouped)
    .sort()
    .map(date => ({
      label: date,
      value: metricFn(grouped[date]),   // <= теперь metricFn вернёт ЧИСЛО
    }));
}

// Сумма total_price за день
function sumTotalPrice(data) {
  return data.reduce((sum, item) => sum + (parseFloat(item.total_price) || 0), 0);
}

// Количество заказов за день
function countOrders(data) {
  return data.length;
}

// Количество отменённых заказов за день
function countCanceledOrders(data) {
  return data.filter(item => item.status === true).length;
}

// Средняя скидка за день
function avgDiscountPerDay(data) {
  if (!data.length) return 0;
  const total = data.reduce((sum, item) => sum + (parseFloat(item.discount) || 0), 0);
  return total / data.length;
}



// Вотчер для дат
watch(
  () => filters.value.dateRange,
  (newDates) => {
    if (Array.isArray(newDates) && newDates.length === 2) {
      const dateRange = buildDateRange(newDates);
      if (dateRange) {
        fetchData(dateRange);
      } else {
        tableData.value = [];
      }
    } else {
      tableData.value = [];
    }
  },
  { deep: true }
);


const totalPriceData = ref([]);


const ordersCountData = ref([]);


const canceledOrdersData = ref([]);


const discountData = ref([]);


const totalPriceTrend = ref([]);
const ordersCountTrend = ref([]);
const canceledOrdersTrend = ref([]);
const discountTrend = ref([]);

const metrics = computed(() => [
  {
    key: 'total_price',
    dataForChart: totalPriceTrend.value, // <-- тут ставим тренд
    topData: totalPriceData.value,       // <-- а здесь топ
  },
  {
    key: 'canceled',
    dataForChart: canceledOrdersTrend.value,
    topData: canceledOrdersData.value,
  },
  {
    key: 'discount',
    dataForChart: discountTrend.value,
    topData: discountData.value,
  },
  {
    key: 'sales_count',
    dataForChart: ordersCountTrend.value,
    topData: ordersCountData.value,
  },
]);



const columnsForTopTable = [
  { label: "Артикул", field: "nm_id" },
  { label: "Текущее значение", field: "current" },
  { label: "Предыдущее значение", field: "previous" },
  { label: "Изменение %", field: "percentChange", formatter: (val) => val.toFixed(2) + "%" },
  { label: "Тренд", field: "percentChange", formatter: val => val > 0 ? "⬆️" : val < 0 ? "⬇️" : "➖" },
];
</script>

<style>


canvas {
  image-rendering: pixelated;
}
</style>
