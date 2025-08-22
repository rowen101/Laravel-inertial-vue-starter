<template>
  <SkeletonLoader v-if="loading" type="card" :columns="4" :itemCount="4" />
  <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
    <DataSectionItem
      v-for="metric in dashboardMetrics"
      :key="metric.id"
      :title="metric.title"
      :value="metric.value"
      :icon-background="metric.iconBackground"
      :icon-color="metric.iconColor"
    >
      <template #icon>
        <VaIcon :name="metric.icon" size="large" />
      </template>
    </DataSectionItem>
  </div>
</template>

<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue'
import { useColors } from 'vuestic-ui'
import DataSectionItem from './DataSectionItem.vue'
import { dashboard } from '../dashboard/type'

interface DashboardMetric {
  id: string
  title: string
  value: string
  icon: string
  iconBackground: string
  iconColor: string
}

const { getColor } = useColors()
const headerStat = ref<dashboard[]>([])
const loading = ref(true)

const fetchlists = async () => {
  try {
    const batchList = await getCardheader()
    headerStat.value = batchList
  } catch (error) {
    console.error('Error fetching batch list:', error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchlists()
})

const safeSum = (...values: (number | null)[]): number => {
  return values.reduce((sum: number, val) => sum + (val || 0), 0)
}

const dashboardMetrics = computed<DashboardMetric[]>(() => {
  // Return empty array if still loading
  if (loading.value) return []

  const { BULKSTORAGE = 0, BULKPICK = 0, CASEPICK = 0, RESTRICTED = 0, RECEIVING = 0, SHIPPING = 0 } = headerStat.value

  const totalInventory = safeSum(BULKSTORAGE, BULKPICK, CASEPICK, RESTRICTED, RECEIVING, SHIPPING).toLocaleString(
    'en-US',
  )

  return [
    {
      id: 'inventoryTotal',
      title: 'INVENTORY TOTAL',
      value: totalInventory,
      icon: 'mso-pallet',
      iconBackground: getColor('success'),
      iconColor: getColor('on-success'),
    },
    {
      id: 'bulkStorage',
      title: 'BULK STORAGE',
      value: (BULKSTORAGE || 0).toLocaleString('en-US'),
      icon: 'mso-pallet',
      iconBackground: getColor('info'),
      iconColor: getColor('on-info'),
    },
    {
      id: 'casePick',
      title: 'CASE PICK',
      value: (CASEPICK || 0).toLocaleString('en-US'),
      icon: 'mso-pallet',
      iconBackground: getColor('warning'),
      iconColor: getColor('on-warning'),
    },
    {
      id: 'restricted',
      title: 'RESTRICTED',
      value: (RESTRICTED || 0).toLocaleString('en-US'),
      icon: 'mso-pallet',
      iconBackground: getColor('danger'),
      iconColor: getColor('on-danger'),
    },
  ]
})
</script>
