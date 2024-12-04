<script setup>
import { containerMaxW } from "@/config.js";
import BaseLevel from "@/Components/BaseLevel.vue";
import AppLogo from "@/Components/AppLogo.vue";
import { router } from '@inertiajs/vue3'

const env = router.page.props.environment.toString().toLowerCase();
const appName = router.page.props.appname;
const year = new Date().getFullYear();
</script>

<template>
  <footer class="py-2 px-6" :class="containerMaxW">
    <BaseLevel>
      <div class="text-center md:text-left">
        <b>&copy;{{ year }},
          <a href="https://codesafesolution.com/" target="_blank">Codesafe IT Solution</a>.
        </b>
        <slot />
      </div>
      <div class="flex md:py-2">
        <span v-if="env !== 'production'" class="mr-4 capitalize">{{ env }} environment</span>
        <a href="https://codesafesolution.com" target="_blank" class="flex">
          <AppLogo class="w-auto h-8 md:h-6" />
          <span class="ml-2">{{appName}}</span>
        </a>
      </div>
    </BaseLevel>
  </footer>
</template>
