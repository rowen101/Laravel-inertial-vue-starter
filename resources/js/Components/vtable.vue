<template>
  <VaCard>
    <VaCardContent>
      <!-- Search and Filter Section -->
      <div class="flex flex-col sm:flex-row gap-2 mb-2 justify-between">
        <div class="flex flex-col md:flex-row gap-2 justify-start">
          <div class="flex gap-1 justify-end relative">
            <VaInput v-if="showOptionalInput" v-model="optionalInputValue" class="w-10" size="small">
              <template #appendInner>
                <VaButton
                  size="small"
                  preset="plain"
                  color="primary"
                  icon="edit"
                  class="!p-0 absolute right-1 top-1/2 transform -translate-y-1/2"
                  @click="$emit('data-retrieve-item', optionalInputValue)"
                /> </template
            ></VaInput>
          </div>

          <VaInput v-model="filters.search" placeholder="Search">
            <template #prependInner>
              <VaIcon name="search" color="secondary" size="small" />
            </template>
          </VaInput>
        </div>
        <VaButton    v-if="btnlabel !== null && btnlabel !== undefined"  :icon="btnIcon" gradient :color="colors" @click="$emit('add-item')">{{ btnlabel }}</VaButton>
      </div>

      <!-- Desktop Table View -->
      <div class="desktop-table">
        <VaDataTable
          v-model:sort-by="sortBy"
          v-model:sorting-order="sortingOrder"
          :columns="processedColumns"
          :items="paginatedItems"
          :loading="loading"
          hoverable
          sticky-header
          class="w-full"
        >
          <!-- Handle clickable columns -->
          <template v-for="column in clickableColumns" #[`cell(${column})`]="{ rowData }">
            <span class="text-primary cursor-pointer hover:underline" @click="handleColumnClick(rowData)">
              {{ rowData[column] }}
            </span>
          </template>

          <!-- Pass through other slots -->
          <template v-for="(slot, name) in $slots" #[name]="slotProps">
            <slot :name="name" v-bind="slotProps" />
          </template>
        </VaDataTable>
      </div>
      <!-- Totals Row -->
      <div v-if="showTotals && numericColumns.length > 0" class="totals-row">
        <div class="flex justify-end gap-4 pr-4 py-2 bg-gray-50">
          <div v-for="col in numericColumns" :key="col.key" class="text-right">
            <div class="font-semibold text-xs text-gray-500">{{ col.totalLabel || `${col.label}` }} Total</div>
            <div>
              {{ col.formatTotal ? col.formatTotal(calculateColumnTotal(col.key)) : calculateColumnTotal(col.key) }}
            </div>
          </div>
        </div>
      </div>

      <!-- Mobile Card View -->
      <div class="mobile-table">
        <MobileTableCard
          :items="paginatedItems"
          :columns="processedColumns"
          :clickable-columns="clickableColumns"
          @rowClick="handleColumnClick"
        />
      </div>

      <!-- Pagination - Only show if pagination is enabled -->
      <div v-if="localPagination" class="flex flex-col-reverse sm:flex-row gap-2 justify-between items-center py-2">
        <div>
          <b>{{ localPagination.total }} results.</b>
          Results per page
          <VaSelect v-model="localPagination.perPage" class="!w-20" :options="[10, 50, 100]" />
        </div>

        <div v-if="totalPages > 1" class="flex">
          <VaButton
            preset="secondary"
            icon="va-arrow-left"
            aria-label="Previous page"
            :disabled="localPagination.page === 1"
            @click="localPagination.page--"
          />
          <VaButton
            class="mr-2"
            preset="secondary"
            icon="va-arrow-right"
            aria-label="Next page"
            :disabled="localPagination.page === totalPages"
            @click="localPagination.page++"
          />
          <VaPagination
            v-model="localPagination.page"
            buttons-preset="secondary"
            :pages="totalPages"
            :visible-pages="5"
            :boundary-links="false"
            :direction-links="false"
          />
        </div>
        <div>
          <VaButton color="info" class="mr-6 mb-2" size="small" @click="exportToCSV"> Export CSV </VaButton>
        </div>
      </div>
    </VaCardContent>
  </VaCard>
</template>

<script lang="ts">
import { defineComponent, computed, ref, watch } from 'vue'
import MobileTableCard from './MobileTableCard.vue'

interface Column {
  key: string
  label: string
  sortable?: boolean
  clickable?: boolean
  numeric?: boolean
  totalLabel?: string
  formatTotal?: (value: number) => string | number
}

interface Pagination {
  page: number
  perPage: number
  total: number
}

interface Filters {
  isActive: boolean | null
  search: string
}

export default defineComponent({
  name: 'ReusableTable',
  components: {
    MobileTableCard,
  },
  props: {
    columns: {
      type: Array as () => Column[],
      required: true,
      validator: (columns: any) =>
        columns.every((col: any) => {
          return (
            typeof col.key === 'string' &&
            typeof col.label === 'string' &&
            (typeof col.sortable === 'boolean' || col.sortable === undefined) &&
            (typeof col.clickable === 'boolean' || col.clickable === undefined) &&
            (typeof col.numeric === 'boolean' || col.numeric === undefined)
          )
        }),
    },
    btnIcon: {
      type: String,
      default: 'add',
    },
    colors: {
      type: String,
      default: null,
    },
    btnlabel: {
      type: String,
      default: null,
    },
    items: {
      type: Array as () => any[],
      required: true,
    },
    loading: {
      type: Boolean,
      default: false,
    },
    pagination: {
      type: Object as () => Pagination | null,
      default: null,
    },
    clickableColumn: {
      type: String,
      default: null,
    },
    showTotals: {
      type: Boolean,
      default: true,
    },
    showOptionalInput: {
      type: Boolean,
      default: false,
    },
    optionalInputName: {
      type: String,
      default: 'text',
    },
    optionalInputDefaultValue: {
      type: String,
      default: '',
    },
    buttonText: {
      type: String,
      default: null,
    },
    enablePaste: {
      type: Boolean,
      default: false,
    },
    pasteMapping: {
      type: Object as () => Record<string, number>, // Maps column keys to Excel columns
      default: () => ({}),
    },
    onPaste: {
      type: Function as PropType<(data: any[]) => Promise<any[]>>,
      default: null,
    }
  },
  emits: ['add-item', 'edit-item', 'delete-item', 'update:pagination', 'row-click', 'data-retrieve-item'],
  setup(props, { emit }) {
    const filters = ref<Filters>({
      isActive: null,
      search: '',
    })

    const sortBy = ref<string>('')
    const sortingOrder = ref<'asc' | 'desc'>('asc')

    // Create local copy of pagination
    const localPagination = ref<Pagination | null>(props.pagination ? { ...props.pagination } : null)

    // Emit changes when local pagination updates
    watch(
      localPagination,
      (newVal) => {
        if (newVal) {
          emit('update:pagination', newVal)
        }
      },
      { deep: true },
    )

    const totalPages = computed(() => {
      if (!localPagination.value) return 0
      return Math.ceil(localPagination.value.total / localPagination.value.perPage)
    })

    // Process columns to add sorting functionality
    const processedColumns = computed(() => {
      return props.columns.map((col) => ({
        ...col,
        sortable: col.sortable !== undefined ? col.sortable : true,
      }))
    })

    // Get list of clickable columns (either marked with clickable:true or matching clickableColumn prop)
    const clickableColumns = computed(() => {
      if (props.clickableColumn) {
        return [props.clickableColumn]
      }
      return props.columns.filter((col) => col.clickable).map((col) => col.key)
    })

    const filteredItems = computed(() => {
      const searchTerm = filters.value.search.toLowerCase()

      let filtered = props.items.filter((item) => {
        return props.columns.some((col) => {
          const columnKey = col.key
          const columnValue = item[columnKey]

          if (typeof columnValue !== 'string') return false

          return columnValue.toLowerCase().includes(searchTerm)
        })
      })
      // Apply sorting
      if (sortBy.value && sortingOrder.value) {
        filtered = filtered.sort((a, b) => {
          const aValue = a[sortBy.value]
          const bValue = b[sortBy.value]
          if (aValue < bValue) return sortingOrder.value === 'asc' ? -1 : 1
          if (aValue > bValue) return sortingOrder.value === 'asc' ? 1 : -1
          return 0
        })
      }

      return filtered
    })

    // Paginate the filtered items if pagination is enabled
    const paginatedItems = computed(() => {
      if (!localPagination.value) {
        return filteredItems.value
      }

      const start = (localPagination.value.page - 1) * localPagination.value.perPage
      const end = start + localPagination.value.perPage
      return filteredItems.value.slice(start, end)
    })

    const handleColumnClick = (rowData: any) => {
      emit('row-click', rowData)
    }

    const exportToCSV = () => {
      // Get the column headers
      const headers = props.columns.map((col) => col.label)

      // Get the data for all filtered items (not just paginated ones)
      const data = filteredItems.value.map((item) => {
        return props.columns.map((col) => {
          // Handle nested properties if needed
          const value = item[col.key]
          // Escape quotes and wrap in quotes if contains commas
          if (typeof value === 'string' && (value.includes(',') || value.includes('"'))) {
            return `"${value.replace(/"/g, '""')}"`
          }
          return value !== undefined ? value : ''
        })
      })

      // Combine headers and data
      const csvContent = [headers.join(','), ...data.map((row) => row.join(','))].join('\n')

      // Create download link
      const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' })
      const url = URL.createObjectURL(blob)
      const link = document.createElement('a')
      link.setAttribute('href', url)
      link.setAttribute('download', 'export.csv')
      link.style.visibility = 'hidden'
      document.body.appendChild(link)
      link.click()
      document.body.removeChild(link)
    }

    // Only include columns explicitly marked as numeric
    const numericColumns = computed(() => {
      return props.columns.filter((col) => col.numeric)
    })

    // Calculate total for a specific column
    const calculateColumnTotal = (columnKey: string) => {
      return filteredItems.value.reduce((sum, item) => {
        const value = parseFloat(item[columnKey]) || 0
        return sum + value
      }, 0)
    }
    const optionalInputValue = ref(props.optionalInputDefaultValue)

    const handleButtonClick = () => {
      emit('data-retrieve-item', optionalInputValue.value)
      if (!props.buttonText) {
        // Default behavior if no custom button handler
        emit('add-item')
      }
    }

    watch(
      () => localPagination.value?.perPage,
      () => {
        if (localPagination.value) {
          localPagination.value.page = 1
        }
      },
    )

    return {
      optionalInputValue,
      handleButtonClick,
      numericColumns,
      calculateColumnTotal,
      exportToCSV,
      filters,
      sortBy,
      sortingOrder,
      totalPages,
      processedColumns,
      clickableColumns,
      filteredItems,
      paginatedItems,
      localPagination,
      handleColumnClick,
    }
  },
})
</script>

<style lang="scss" scoped>
.icon-on-small :deep(.va-button__content) {
  @media (max-width: 640px) {
    padding: 0;
  }
}
.hidden {
  display: none;
}
.sm\:inline {
  @media (min-width: 640px) {
    display: inline;
  }
}
.va-data-table {
  ::v-deep(.va-data-table__table-tr) {
    border-bottom: 1px solid var(--va-background-border);
  }
}
.compact-table {
  font-size: 0.8rem;
}

/* Reduce cell padding */
.compact-table :deep(.va-data-table__table-tr) {
  height: 32px !important;
}

.compact-table :deep(.va-data-table__table-td) {
  padding: 4px 8px !important;
}

/* Compact pagination */
.compact-table :deep(.va-pagination__content) {
  margin-top: 8px !important;
}
.totals-row {
  border-top: 1px solid var(--va-background-border);
  margin-top: -1px; // Align with table border
  font-size: 0.9rem;

  div {
    min-width: 100px;
  }
}
.desktop-table {
  display: block;
}
.mobile-table {
  display: none;
}
/* Mobile view (below 768px) */
@media (max-width: 767px) {
  .desktop-table {
    display: none; /* Hide VaDataTable */
  }
  .mobile-table {
    display: block; /* Show MobileTableCard */
  }
}

/* Desktop view (768px and above) */
@media (min-width: 768px) {
  .desktop-table {
    display: block; /* Show VaDataTable */
  }
  .mobile-table {
    display: none; /* Hide MobileTableCard */
  }
}
@media only screen and (max-width: 600px) {
  body {
    background-color: lightblue;
  }
}
</style>
