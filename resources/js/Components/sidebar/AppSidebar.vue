<template>
  <SkeletonLoader v-if="loading" type="side-menu" :item-count="8" />
  <VaSidebar v-else v-model="writableVisible" :width="sidebarWidth" :color="color" minimized-width="0">
    <!-- Static Dashboard Button at Top -->
    <VaSidebarItem
      to="/dashboard"
      :active="route?.path === '/dashboard'"
      :active-color="activeColor"
      text-color="textPrimary"
      aria-label="Visit Dashboard"
      hover-opacity="0.10"
    >
      <VaSidebarItemContent class="py-3 pr-2 pl-4">
        <VaIcon
          aria-hidden="true"
          name="dashboard"
          size="20px"
          :color="route?.path === '/dashboard' ? 'primary' : 'secondary'"
        />
        <VaSidebarItemTitle class="flex justify-between items-center leading-5 font-semibold">
          Dashboard
        </VaSidebarItemTitle>
      </VaSidebarItemContent>
    </VaSidebarItem>

    <!-- Menu Items -->
    <VaAccordion v-model="value" multiple>
      <VaCollapse v-for="(item, index) in menuItems" :key="index">
        <template #header="{ value: isCollapsed }">
          <VaSidebarItem
            :to="item.route ? { path: item.route } : undefined"
            :active="routeHasActiveChild(item)"
            :active-color="activeColor"
            :text-color="textColor(item)"
            :aria-label="`${item.items.length ? 'Open category ' : 'Visit'} ${item.title}`"
            role="button"
            hover-opacity="0.10"
          >
            <VaSidebarItemContent class="py-3 pr-2 pl-4">
              <VaIcon v-if="item.icon" aria-hidden="true" :name="item.icon" size="20px" :color="iconColor(item)" />
              <VaSidebarItemTitle class="flex justify-between items-center leading-5 font-semibold">
                {{ item.title }}
                <VaIcon v-if="item.items.length" :name="arrowDirection(isCollapsed)" size="20px" />
              </VaSidebarItemTitle>
            </VaSidebarItemContent>
          </VaSidebarItem>
        </template>
        <template #body>
          <div v-for="(childItem, index2) in item.items" :key="index2">
            <VaSidebarItem
              :to="{ path: childItem.route }"
              :active="isActiveChildRoute(childItem)"
              :active-color="activeColor"
              :text-color="textColor(childItem)"
              :aria-label="`Visit ${childItem.title}`"
              hover-opacity="0.10"
            >
              <VaSidebarItemContent class="py-3 pr-2 pl-11">
                <VaSidebarItemTitle class="leading-5 font-semibold">
                  {{ childItem.title }}
                </VaSidebarItemTitle>
              </VaSidebarItemContent>
            </VaSidebarItem>
          </div>
        </template>
      </VaCollapse>
    </VaAccordion>

    <!-- Static Logout Button at Bottom -->
    <VaSidebarItem class="mt-auto" text-color="textPrimary" aria-label="Logout" hover-opacity="0.10" @click="logout">
      <VaSidebarItemContent class="py-3 pr-2 pl-4">
        <VaIcon aria-hidden="true" name="logout" size="20px" color="secondary" />
        <VaSidebarItemTitle class="flex justify-between items-center leading-5 font-semibold">
          Logout
        </VaSidebarItemTitle>
      </VaSidebarItemContent>
    </VaSidebarItem>
  </VaSidebar>
</template>

<script lang="ts">
import { onMounted, ref, computed, watch } from 'vue'
import { useToast } from 'vuestic-ui'
import { defineComponent } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useColors } from 'vuestic-ui'
import { router } from '@inertiajs/vue3'

export interface IMenuItem {
  title: string
  menuID: number
  icon: string | null
  route: string | null
  items: IMenuItem[]
}

export default defineComponent({
  name: 'Sidebar',
  props: {
    visible: { type: Boolean, default: true },
    mobile: { type: Boolean, default: false },
  },
  emits: ['update:visible'],

  setup(props, { emit }) {
    const { getColor, colorToRgba } = useColors()
    const route = useRoute()
    const router = useRouter()
    const { notify } = useToast()


    const value = ref<boolean[]>([])
    const menuItems = ref<IMenuItem[]>([])

    const writableVisible = computed({
      get: () => props.visible,
      set: (v: boolean) => emit('update:visible', v),
    })

    const loading = ref(false)
    const isActiveChildRoute = (child: IMenuItem) => {
      return child.route ? route?.path === child.route : false
    }

    const routeHasActiveChild = (section: IMenuItem) => {
      if (!section.items || section.items.length === 0) {
        return section.route ? route?.path === section.route : false
      }
      return section.items.some((child) => isActiveChildRoute(child))
    }

    const setActiveExpand = () => {
      value.value = menuItems.value.map((item: IMenuItem) => routeHasActiveChild(item))
    }

    const sidebarWidth = computed(() => (props.mobile ? '100vw' : '280px'))
    const color = computed(() => getColor('background-secondary'))
    const activeColor = computed(() => colorToRgba(getColor('focus'), 0.1))

    const iconColor = (item: IMenuItem) => (routeHasActiveChild(item) ? 'primary' : 'secondary')
    const textColor = (item: IMenuItem) => (routeHasActiveChild(item) ? 'primary' : 'textPrimary')
    const arrowDirection = (state: boolean) => (state ? 'va-arrow-up' : 'va-arrow-down')

    const logout = async () => {
      try {
        router.post('/logout')
      } catch (error) {
        notify({
          message: error instanceof Error ? error.message : 'Logout failed',
          color: 'danger',
        })
      }
    }

    const fetchMenuItems = async () => {
      loading.value = true
      try {
        // Dummy TMS (Trucking Management System) menu items
        menuItems.value = [
          {
            title: 'Fleet Management',
            menuID: 1,
            icon: 'folder_shared',
            route: null,
            items: [
              { title: 'Vehicle Registration', menuID: 11, icon: null, route: '/fleet/vehicles', items: [] },
              { title: 'Driver Management', menuID: 12, icon: null, route: '/fleet/drivers', items: [] },
              { title: 'Maintenance Schedule', menuID: 13, icon: null, route: '/fleet/maintenance', items: [] },
              { title: 'Fuel Management', menuID: 14, icon: null, route: '/fleet/fuel', items: [] },
              { title: 'Insurance Tracking', menuID: 15, icon: null, route: '/fleet/insurance', items: [] }
            ]
          },
          {
            title: 'Load Management',
            menuID: 2,
            icon: 'folder_shared',
            route: null,
            items: [
              { title: 'Load Planning', menuID: 21, icon: null, route: '/loads/planning', items: [] },
              { title: 'Load Tracking', menuID: 22, icon: null, route: '/loads/tracking', items: [] },
              { title: 'Load History', menuID: 23, icon: null, route: '/loads/history', items: [] },
              { title: 'Route Optimization', menuID: 24, icon: null, route: '/loads/routes', items: [] }
            ]
          },
          {
            title: 'Customer Management',
            menuID: 3,
            icon: 'folder_shared',
            route: null,
            items: [
              { title: 'Customer Database', menuID: 31, icon: null, route: '/customers/database', items: [] },
              { title: 'Customer Contracts', menuID: 32, icon: null, route: '/customers/contracts', items: [] },
              { title: 'Rate Management', menuID: 33, icon: null, route: '/customers/rates', items: [] },
              { title: 'Customer Portal', menuID: 34, icon: null, route: '/customers/portal', items: [] }
            ]
          },
          {
            title: 'Dispatch & Operations',
            menuID: 4,
            icon: 'folder_shared',
            route: null,
            items: [
              { title: 'Dispatch Board', menuID: 41, icon: null, route: '/dispatch/board', items: [] },
              { title: 'Driver Communication', menuID: 42, icon: null, route: '/dispatch/communication', items: [] },
              { title: 'Real-time Tracking', menuID: 43, icon: null, route: '/dispatch/tracking', items: [] },
              { title: 'Emergency Response', menuID: 44, icon: null, route: '/dispatch/emergency', items: [] }
            ]
          },
          {
            title: 'Financial Management',
            menuID: 5,
            icon: 'folder_shared',
            route: null,
            items: [
              { title: 'Invoicing', menuID: 51, icon: null, route: '/finance/invoicing', items: [] },
              { title: 'Accounts Receivable', menuID: 52, icon: null, route: '/finance/receivables', items: [] },
              { title: 'Expense Tracking', menuID: 53, icon: null, route: '/finance/expenses', items: [] },
              { title: 'Payroll Management', menuID: 54, icon: null, route: '/finance/payroll', items: [] },
              { title: 'Financial Reports', menuID: 55, icon: null, route: '/finance/reports', items: [] }
            ]
          },
          {
            title: 'Compliance & Safety',
            menuID: 6,
            icon: 'folder_shared',
            route: null,
            items: [
              { title: 'DOT Compliance', menuID: 61, icon: null, route: '/compliance/dot', items: [] },
              { title: 'Driver Logs (ELD)', menuID: 62, icon: null, route: '/compliance/logs', items: [] },
              { title: 'Safety Records', menuID: 63, icon: null, route: '/compliance/safety', items: [] },
              { title: 'Inspection Reports', menuID: 64, icon: null, route: '/compliance/inspections', items: [] },
              { title: 'Training Management', menuID: 65, icon: null, route: '/compliance/training', items: [] }
            ]
          },
          {
            title: 'Reports & Analytics',
            menuID: 7,
            icon: 'folder_shared',
            route: null,
            items: [
              { title: 'Performance Dashboard', menuID: 71, icon: null, route: '/reports/performance', items: [] },
              { title: 'Fleet Utilization', menuID: 72, icon: null, route: '/reports/utilization', items: [] },
              { title: 'Revenue Analysis', menuID: 73, icon: null, route: '/reports/revenue', items: [] },
              { title: 'Cost Analysis', menuID: 74, icon: null, route: '/reports/costs', items: [] },
              { title: 'Custom Reports', menuID: 75, icon: null, route: '/reports/custom', items: [] }
            ]
          },
          {
            title: 'System Administration',
            menuID: 8,
            icon: 'settings',
            route: null,
            items: [
              { title: 'User Management', menuID: 81, icon: null, route: '/admin/users', items: [] },
              { title: 'Role Permissions', menuID: 82, icon: null, route: '/admin/permissions', items: [] },
              { title: 'System Settings', menuID: 83, icon: null, route: '/admin/settings', items: [] },
              { title: 'Data Backup', menuID: 84, icon: null, route: '/admin/backup', items: [] },
              { title: 'Audit Logs', menuID: 85, icon: null, route: '/admin/logs', items: [] }
            ]
          }
        ]
      } catch (error) {
        notify({
          message: error instanceof Error ? error.message : 'Failed to load menu items',
          color: 'danger',
        })
        menuItems.value = []
      } finally {
        loading.value = false
      }
    }

    watch(() => route?.fullPath, setActiveExpand, { immediate: true })

    onMounted(() => {
      fetchMenuItems()
    })

    return {
      loading,
      writableVisible,
      sidebarWidth,
      value,
      color,
      activeColor,
      menuItems,
      route,
      routeHasActiveChild,
      isActiveChildRoute,
      iconColor,
      textColor,
      arrowDirection,
      logout,
    }
  },
})
</script>

<style scoped>
/* This ensures the logout button stays at the bottom */
.va-sidebar__menu {
  display: flex;
  flex-direction: column;
  height: 100%;
}
</style>
