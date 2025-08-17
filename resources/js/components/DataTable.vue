<template>
  <div class="h-full">
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
