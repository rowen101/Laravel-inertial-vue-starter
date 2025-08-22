<template>
  <VaDropdown :offset="[13, 0]" class="notification-dropdown" stick-to-edges :close-on-content-click="false">
    <template #anchor>
      <VaButton preset="secondary" color="textPrimary">
        <VaBadge overlap>
          <p>Hi! {{ user?.name || 'Guest' }}</p>
        </VaBadge>
      </VaButton>
    </template>
    <VaDropdownContent class="h-full sm:max-w-[420px] sm:h-auto">
      <section class="sm:max-h-[320px] p-4 overflow-auto">
        <VaList class="space-y-1 mb-2">
          <template v-for="(item, index) in notificationsWithRelativeTime" :key="item.id">
            <VaListItem class="text-base" @click="handleNotificationClick(item)">
              <VaListItemSection icon class="mx-0 p-0">
                <VaIcon :name="item.icon" color="secondary" />
              </VaListItemSection>
              <VaListItemSection>
                {{ item.message }}
              </VaListItemSection>
              <VaListItemSection icon class="mx-1">
                {{ item.updateTimestamp }}
              </VaListItemSection>
            </VaListItem>
            <VaListSeparator v-if="item.separator && index !== notificationsWithRelativeTime.length - 1" class="mx-3" />
          </template>
        </VaList>
      </section>
    </VaDropdownContent>
  </VaDropdown>

  <!-- Warehouse Change Modal -->
  <VaModal v-model="showWarehouseModal" title="Change Warehouse" size="small">
    <div class="p-4">
      <p>Select a new warehouse:</p>
      <VaSelect
        v-model="selectedWarehouse"
        :options="warehouseOptions"
        class="mt-2"
        label="Warehouse"
      />
      <div class="flex justify-end mt-4 gap-2">
        <VaButton @click="showWarehouseModal = false">Cancel</VaButton>
        <VaButton @click="changeWarehouse">Confirm</VaButton>
      </div>
    </div>
  </VaModal>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import VaIconNotification from '../../../icons/VaIconNotification.vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const user = computed(() => page.props.auth.user)

const showWarehouseModal = ref(false)
const selectedWarehouse = ref('')
const warehouseOptions = ['Main Warehouse', 'East Warehouse', 'West Warehouse', 'North Warehouse']

const notifications = [
  {
    message: 'Change Warehouse',
    icon: 'warehouse',
    id: 1,
    action: 'changeWarehouse',
  },
  {
    message: 'Update Password',
    icon: 'users',
    id: 2,
  },
]

const notificationsWithRelativeTime = computed(() => {
  return notifications.map(item => ({
    ...item,
    updateTimestamp: 'Just now'
  }))
})

const handleNotificationClick = (item) => {
  if (item.action === 'changeWarehouse') {
    showWarehouseModal.value = true
  }
}

const changeWarehouse = () => {
  // Implement warehouse change logic here
  console.log('Changing warehouse to:', selectedWarehouse.value)
  showWarehouseModal.value = false
}
</script>

<style lang="scss" scoped>
.notification-dropdown {
  cursor: pointer;

  .notification-dropdown__icon {
    position: relative;
    display: flex;
    align-items: center;
  }

  .va-dropdown__anchor {
    display: inline-block;
  }
}
</style>
