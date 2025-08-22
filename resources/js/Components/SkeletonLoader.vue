<template>
  <div class="w-full">
    <!-- Header Skeleton (reusable for both card and side menu) -->
    <div v-if="type === 'table'" class="flex mb-4 gap-4 items-center">
      <VaSkeleton variant="rounded" width="10rem" height="2rem" />
      <div class="flex-grow"></div>
      <VaSkeleton variant="rounded" width="8rem" height="2.5rem" />
    </div>

    <!-- Card Loading Skeleton -->
    <div v-else-if="type === 'card'" class="grid gap-4" :class="gridClass">
      <div v-for="i in itemCount" :key="'card-skeleton-' + i" class="border rounded-lg p-4">
        <VaSkeleton variant="rounded" width="100%" height="1.5rem" class="mb-3" />
        <VaSkeleton variant="rounded" width="80%" height="1rem" class="mb-2" />
        <VaSkeleton variant="rounded" width="60%" height="1rem" class="mb-4" />
        <div class="flex justify-between">
          <VaSkeleton variant="rounded" width="30%" height="1.5rem" />
          <VaSkeleton variant="rounded" width="20%" height="1.5rem" />
        </div>
      </div>
    </div>

    <!-- Side Menu Loading Skeleton -->
    <div v-else-if="type === 'side-menu'" class="flex flex-col gap-2">
      <div v-for="i in itemCount" :key="'menu-skeleton-' + i" class="flex items-center gap-3 p-3">
        <VaSkeleton variant="circle" width="1.5rem" height="1.5rem" />
        <VaSkeleton variant="rounded" width="70%" height="1rem" />
      </div>
    </div>

    <!-- Default Table Row Loading Skeleton -->
    <div v-else class="flex flex-col gap-2">
      <div v-for="i in itemCount" :key="'row-skeleton-' + i" class="flex gap-4 p-3">
        <VaSkeleton variant="rounded" width="100%" height="1.5rem" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SkeletonLoader',
  props: {
    type: {
      type: String,
      default: 'table', // 'table', 'card', or 'side-menu'
      validator: (value) => ['table', 'card', 'side-menu'].includes(value),
    },
    itemCount: {
      type: Number,
      default: 5,
    },
    columns: {
      type: Number,
      default: 3,
    },
  },
  computed: {
    gridClass() {
      return {
        'grid-cols-1': this.columns === 1,
        'grid-cols-2': this.columns === 2,
        'grid-cols-3': this.columns === 3,
        'grid-cols-4': this.columns === 4,
      }
    },
  },
}
</script>

<style scoped>
/* Add any custom styles here */
</style>
