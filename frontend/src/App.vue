<template>
  <DefaultLayout />
  <Toast
    v-if="toast.visible"
    :message="toast.message"
    :type="toast.type"
    @close="toast.visible = false"
  />
</template>

<script setup lang="ts">
import DefaultLayout from '@/components/DefaultLayout.vue'
import Toast from './components/ToastComponent.vue'
import { toast } from '@/services/toast'
</script>
