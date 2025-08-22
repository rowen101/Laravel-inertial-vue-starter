<template>
  <div class="mobile-table-container">
    <div v-for="(item, index) in items" :key="index" class="mobile-table-card">
      <div v-for="column in columns" :key="column.key" class="mobile-table-row">
        <div class="mobile-table-label">{{ column.label }}:</div>
        <div
          class="mobile-table-value"
          :class="{ 'text-primary cursor-pointer hover:underline': clickableColumns.includes(column.key) }"
          @click="clickableColumns.includes(column.key) ? $emit('row-click', item) : null"
        >
          {{ item[column.key] }}
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue'

interface Column {
  key: string
  label: string
  sortable?: boolean
  clickable?: boolean
  numeric?: boolean
  totalLabel?: string
  formatTotal?: (value: number) => string | number
}

export default defineComponent({
  name: 'MobileTableCard',
  props: {
    items: {
      type: Array as PropType<any[]>,
      required: true,
    },
    columns: {
      type: Array as PropType<Column[]>,
      required: true,
    },
    clickableColumns: {
      type: Array as PropType<string[]>,
      default: () => [],
    },
  },
  emits: ['row-click'],
})
</script>

<style lang="scss" scoped>
.mobile-table-container {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-bottom: 16px;
}

.mobile-table-card {
  background: white;
  border-radius: 8px;
  padding: 12px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  border: 1px solid var(--va-background-border);
}

.mobile-table-row {
  display: flex;
  justify-content: space-between;
  padding: 6px 0;
  border-bottom: 1px solid #f0f0f0;

  &:last-child {
    border-bottom: none;
  }
}

.mobile-table-label {
  font-weight: 600;
  color: #666;
  font-size: 0.8rem;
}

.mobile-table-value {
  text-align: right;
  font-size: 0.9rem;
  word-break: break-word;
  max-width: 60%;
}
</style>
