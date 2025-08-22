<template>
  <svg :height="height" alt="WMS" fill="none" viewBox="0 0 200 57" xmlns="http://www.w3.org/2000/svg">
    <!-- W -->
    <path
      :fill="colorsComputed.start"
      d="M18.2761 56.1386L0 13.3794H10.5519L18.8968 33.3107L27.2417 13.3794H37.7936L19.5175 56.1386H18.2761Z"
    />
    <!-- M -->
    <path
      :fill="colorsComputed.start"
      d="M62.4836 40.8281V13.3794H72.5527V39.3108C72.5527 44.8281 75.1734 46.7592 78.6907 46.7592C82.5528 46.7592 84.9666 44.8281 84.9666 39.3108V13.3794H95.1047V40.8281C95.1047 50.6213 87.7942 56.1386 78.6907 56.1386C69.2423 56.1386 62.4836 50.3454 62.4836 40.8281Z"
    />
    <!-- S -->
    <path
      :fill="colorsComputed.start"
      d="M122.415 54.7593V13.3794H149.795V22.3451H132.553V29.7935H148.485V38.6211H132.553V46.0695H150.554V54.7593H122.415Z"
    />
    <defs>
      <linearGradient id="paint0_linear" gradientUnits="userSpaceOnUse" x1="0" x2="200" y1="56.1386" y2="56.1386">
        <stop :stop-color="colorsComputed.end" />
        <stop :stop-color="colorsComputed.start" offset="1" />
      </linearGradient>
    </defs>
  </svg>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { useColors } from 'vuestic-ui'

const { getColor } = useColors()

const props = withDefaults(
  defineProps<{
    height?: number
    start?: string
    end?: string
  }>(),
  {
    height: 18,
    start: 'Success',
    end: undefined,
  },
)

const colorsComputed = computed(() => {
  return {
    start: getColor(props.start),
    end: getColor(props.end || props.start),
  }
})
</script>
