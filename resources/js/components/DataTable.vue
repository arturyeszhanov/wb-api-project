<template>
<div>
    <DataTable
      :value="data"
      :paginator="paginator"
      :rows="rowsPerPage"
      :first="currentPageFirst"
      responsiveLayout="scroll"
      @page="onPage"
      @row-click="onRowClick"
      class="styled-datatable rounded-md"
      :style="{ tableLayout: 'fixed', width: '100%' }"
    >
      <Column field="nm_id" header="Артикул" style="width: 18%" />
      <Column
        field="previous"
        header="Предыдущий период"
        style="width: 29%"
        
      />
      <Column
        field="current"
        header="Текущий период"
        style="width: 26%"
      />
      <Column header="Изменение (%)" style="width: 21%">
        <template #body="{ data }">
          <span
            :class="
              (data.percentChange ?? 0) > 0
                ? 'text-green-700'
                : (data.percentChange ?? 0) < 0
                ? 'text-red-700'
                : 'text-black'
            "
          >
            {{ formatPercent(data.percentChange) }}
          </span>
        </template>
      </Column>
      <Column header="" style="width: 6%">
        <template #body="{ data }">
          <span v-if="(data.percentChange ?? 0) > 0">
            <ArrowTrendingUpIcon class="w-8 h-6 text-green-700" stroke-width="3" />
          </span>
          <span v-else-if="(data.percentChange ?? 0) < 0">
            <ArrowTrendingDownIcon class="w-8 h-6 text-red-700" stroke-width="3" />
          </span>
          <span v-else>
            <MinusIcon class="w-8 h-6 text-gray-700" stroke-width="3" />
          </span>
        </template>
      </Column>
    </DataTable>
  </div>
</template>

<script setup>
import DataTable from "primevue/datatable"
import Column from "primevue/column"
import {
  ArrowTrendingUpIcon,
  ArrowTrendingDownIcon,
  MinusIcon,
} from "@heroicons/vue/24/outline"
import { ref, watch } from "vue"
import { useRouter } from "vue-router"

const router = useRouter()

function onRowClick(event) {
  const nmId = event.data.nm_id
  router.push({ name: "ProductDetail", params: { nmId } })
}

const props = defineProps({
  data: {
    type: Array,
    required: true,
  },
  paginator: {
    type: Boolean,
    default: true,
  },
})

const emit = defineEmits(["page-change"])

const rowsPerPage = 10
const currentPageFirst = ref(0)

function onPage(event) {
  currentPageFirst.value = event.first
  emit("page-change", event.first)
}

watch(
  () => props.data,
  () => {
    // При изменении данных сбрасываем пагинацию
    currentPageFirst.value = 0
  }
)

// ✅ безопасное форматирование процентов
function formatPercent(value) {
  if (value == null || isNaN(value)) return "-"
  return `${value.toFixed(2)}%`
}
</script>

<style>
.styled-datatable {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
}

.styled-datatable .p-datatable-wrapper {
    border-top-right-radius: 8px;
    overflow: hidden;
}

.styled-datatable thead th {
    padding: 0.5rem 0.75rem;
    border: 1px solid #e5e7eb;
    background-color: #6b7280;
    color: white;
    font-weight: 400;
    text-align: left;
}

.styled-datatable tbody tr:nth-child(odd) {
    background-color: #f3f4f6;
}

.styled-datatable tbody tr:nth-child(even) {
    background-color: #f9fafb;
}

.styled-datatable tbody tr:hover {
    background-color: #e5e7eb;
    cursor: pointer;
    transition: background-color 0.15s ease-in-out;
}

.styled-datatable tbody td {
    padding: 0.5rem 0.75rem;
    border: 1px solid #e5e7eb;
    text-align: left;
    font-weight: 400;
}

.styled-datatable .p-paginator {
    background-color: #f3f4f6;
    padding: 10px 0;
    justify-content: center;
}

.p-datatable .p-paginator-bottom {
    border: none;
}

.styled-datatable .p-paginator .p-paginator-prev,
.styled-datatable .p-paginator .p-paginator-next,
.styled-datatable .p-paginator .p-paginator-page {
    background-color: white;
    border: 1px solid #6b7280;
    color: #374151;
    margin: 0 2px;
    transition: background-color 0.2s ease;
    min-width: 2.6rem;
    height: 2.6rem;
    font-size: 14px;
}

.styled-datatable .p-paginator .p-paginator-page:hover,
.styled-datatable .p-paginator .p-paginator-prev:hover,
.styled-datatable .p-paginator .p-paginator-next:hover {
    background-color: #6b7280;
    border-color: #6b7280;
    color: white;
    cursor: pointer;
}

.styled-datatable .p-paginator .p-paginator-page.p-highlight {
    background-color: #6b7280;
    color: white;
}
</style>
