<template>
  <VaLayout v-if="breakpoint.lgUp" class="h-screen bg-[var(--va-background-secondary)]">
    <template #left>
        <Link :href="route('dashboard')" style="width: 35vw"  class="bg-primary h-full flex items-center justify-center">Dashboard</Link>

    </template>
    <template #content>
      <main class="h-full flex items-center justify-center mx-auto max-w-[420px]">
       <slot />
      </main>
    </template>
  </VaLayout>

  <VaLayout v-else class="h-screen bg-[var(--va-background-secondary)]">
    <template #content>
      <div class="p-4">
        <main class="h-full flex flex-row items-center justify-start mx-auto max-w-[420px]">
          <div class="flex flex-col items-start">
            <slot />
          </div>
        </main>
      </div>
    </template>
  </VaLayout>
</template>

<script lang="ts" setup>
import { useBreakpoint } from 'vuestic-ui'
import { route } from '../../../vendor/tightenco/ziggy/src/js';
// import VuesticLogo from '../components/VuesticLogo.vue'

const breakpoint = useBreakpoint()
</script>

