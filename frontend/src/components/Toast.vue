<template>
  <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
    <div class="toast align-items-center text-white" :class="[bgClass, 'show']" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">{{ message }}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" @click="$emit('close')"></button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{ message: string; type: 'success' | 'warning' | 'danger' }>()
const bgClass = computed(() => ({
  'bg-success': props.type === 'success',
  'bg-warning': props.type === 'warning',
  'bg-danger': props.type === 'danger'
}))
</script>
