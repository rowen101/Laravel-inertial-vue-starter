<template>
  <VaLayout
    :top="{ fixed: true, order: 2 }"
    :left="{
      fixed: true,
      absolute: breakpoint.mdDown,
      order: 1,
      overlay: breakpoint.mdDown && isSidebarOpen
    }"
    @leftOverlayClick="isSidebarOpen = false"
  >
    <!-- Top Navbar -->
    <template #top>
      <VaNavbar class="app-navbar border-b border-gray-200">
        <template #left>
          <VaButton
            preset="plain"
            icon="menu"
            class="lg:hidden mr-2"
            @click="toggleSidebar"
          />
          <div class="flex items-center">
            <VaIcon name="dashboard" size="24px" color="primary" class="mr-2" />
            <span class="text-xl font-semibold">Laravel App</span>
          </div>
        </template>

        <template #right>
          <VaDropdown placement="bottom-end" :offset="[0, 8]">
            <template #anchor>
              <VaButton preset="plain" class="flex items-center space-x-2 p-2">
                <VaAvatar
                  size="32px"
                  src="https://flowbite.com/docs/images/people/profile-picture-5.jpg"
                  fallback-text="U"
                />
                <VaIcon name="expand_more" size="16px" />
              </VaButton>
            </template>

            <VaDropdownContent class="w-48">
              <div class="p-3 border-b">
                <p class="text-sm font-medium">Neil Sims</p>
                <p class="text-xs text-gray-600">neil.sims@example.com</p>
              </div>

              <VaList class="py-1">
                <VaListItem clickable class="px-3 py-2">
                  <VaListItemSection avatar>
                    <VaIcon name="dashboard" size="16px" />
                  </VaListItemSection>
                  <VaListItemSection>
                    <Link :href="route('dashboard')" class="text-sm">Dashboard</Link>
                  </VaListItemSection>
                </VaListItem>

                <VaListItem clickable class="px-3 py-2">
                  <VaListItemSection avatar>
                    <VaIcon name="settings" size="16px" />
                  </VaListItemSection>
                  <VaListItemSection>
                    <span class="text-sm">Settings</span>
                  </VaListItemSection>
                </VaListItem>

                <VaListItem clickable class="px-3 py-2">
                  <VaListItemSection avatar>
                    <VaIcon name="attach_money" size="16px" />
                  </VaListItemSection>
                  <VaListItemSection>
                    <span class="text-sm">Earnings</span>
                  </VaListItemSection>
                </VaListItem>

                <VaDivider class="my-1" />

                <VaListItem clickable class="px-3 py-2">
                  <VaListItemSection avatar>
                    <VaIcon name="logout" size="16px" />
                  </VaListItemSection>
                  <VaListItemSection>
                    <Link :href="route('logout')" method="post" class="text-sm">Sign out</Link>
                  </VaListItemSection>
                </VaListItem>
              </VaList>
            </VaDropdownContent>
          </VaDropdown>
        </template>
      </VaNavbar>
    </template>

    <!-- Left Sidebar -->
    <template #left>
      <VaSidebar
        :model-value="!breakpoint.mdDown || isSidebarOpen"
        :minimized="false"
        :width="256"
        class="app-sidebar"
      >
        <VaSidebarItem>
          <VaSidebarItemContent>
            <VaIcon name="dashboard" size="20px" />
            <VaSidebarItemTitle>
              <Link :href="route('dashboard')" class="flex-1">Dashboard</Link>
            </VaSidebarItemTitle>
          </VaSidebarItemContent>
        </VaSidebarItem>

        <VaSidebarItem>
          <VaSidebarItemContent>
            <VaIcon name="shopping_cart" size="20px" />
            <VaSidebarItemTitle>E-commerce</VaSidebarItemTitle>
            <VaIcon name="expand_more" size="16px" />
          </VaSidebarItemContent>
          <VaSidebarItem>
            <VaSidebarItemContent class="pl-8">
              <span class="text-sm">Products</span>
            </VaSidebarItemContent>
          </VaSidebarItem>
          <VaSidebarItem>
            <VaSidebarItemContent class="pl-8">
              <span class="text-sm">Billing</span>
            </VaSidebarItemContent>
          </VaSidebarItem>
          <VaSidebarItem>
            <VaSidebarItemContent class="pl-8">
              <span class="text-sm">Invoice</span>
            </VaSidebarItemContent>
          </VaSidebarItem>
        </VaSidebarItem>

        <VaSidebarItem>
          <VaSidebarItemContent>
            <VaIcon name="widgets" size="20px" />
            <VaSidebarItemTitle>
              <Link :href="route('sli/user')" class="flex-1">Asset Monitoring</Link>
            </VaSidebarItemTitle>
          </VaSidebarItemContent>
        </VaSidebarItem>

        <VaSidebarItem>
          <VaSidebarItemContent>
            <VaIcon name="inbox" size="20px" />
            <VaSidebarItemTitle>Inbox</VaSidebarItemTitle>
            <VaBadge text="3" color="primary" class="ml-auto" />
          </VaSidebarItemContent>
        </VaSidebarItem>

        <VaSidebarItem>
          <VaSidebarItemContent>
            <VaIcon name="people" size="20px" />
            <VaSidebarItemTitle>Users</VaSidebarItemTitle>
          </VaSidebarItemContent>
        </VaSidebarItem>

        <VaSidebarItem>
          <VaSidebarItemContent>
            <VaIcon name="inventory" size="20px" />
            <VaSidebarItemTitle>Products</VaSidebarItemTitle>
          </VaSidebarItemContent>
        </VaSidebarItem>

        <VaSidebarItem>
          <VaSidebarItemContent>
            <VaIcon name="login" size="20px" />
            <VaSidebarItemTitle>
              <Link :href="route('login')" class="flex-1">Sign In</Link>
            </VaSidebarItemTitle>
          </VaSidebarItemContent>
        </VaSidebarItem>

        <VaSidebarItem>
          <VaSidebarItemContent>
            <VaIcon name="person_add" size="20px" />
            <VaSidebarItemTitle>
              <Link :href="route('register')" class="flex-1">Sign Up</Link>
            </VaSidebarItemTitle>
          </VaSidebarItemContent>
        </VaSidebarItem>

        <VaDivider class="my-4" />

        <VaSidebarItem>
          <VaSidebarItemContent>
            <VaIcon name="upgrade" size="20px" />
            <VaSidebarItemTitle>Upgrade to Pro</VaSidebarItemTitle>
          </VaSidebarItemContent>
        </VaSidebarItem>

        <VaSidebarItem>
          <VaSidebarItemContent>
            <VaIcon name="description" size="20px" />
            <VaSidebarItemTitle>Documentation</VaSidebarItemTitle>
          </VaSidebarItemContent>
        </VaSidebarItem>

        <VaSidebarItem>
          <VaSidebarItemContent>
            <VaIcon name="layers" size="20px" />
            <VaSidebarItemTitle>Components</VaSidebarItemTitle>
          </VaSidebarItemContent>
        </VaSidebarItem>

        <VaSidebarItem>
          <VaSidebarItemContent>
            <VaIcon name="help" size="20px" />
            <VaSidebarItemTitle>Help</VaSidebarItemTitle>
          </VaSidebarItemContent>
        </VaSidebarItem>
      </VaSidebar>
    </template>

    <!-- Main Content -->
    <template #content>
      <div class="p-4">
        <slot />
      </div>
    </template>
  </VaLayout>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { useBreakpoint } from 'vuestic-ui'
import { route } from '../../../vendor/tightenco/ziggy/src/js';
// import VuesticLogo from '../components/VuesticLogo.vue'

const breakpoint = useBreakpoint()
const isSidebarOpen = ref(false)

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value
}
</script>

<style scoped>
.app-navbar {
  backdrop-filter: blur(8px);
}

.app-sidebar {
  border-right: 1px solid var(--va-background-border);
}
</style>
