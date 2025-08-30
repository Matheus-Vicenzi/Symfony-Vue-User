<template>
  <div>
    <AppHeader />
    <main class="container my-4">
      <router-view />
    </main>
  </div>
</template>

<script setup lang="ts">
import AppHeader from '@/components/AppHeader.vue'
</script>
