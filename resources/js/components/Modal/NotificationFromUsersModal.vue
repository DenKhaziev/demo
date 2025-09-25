<script setup>
import { useNotificationStore } from '@/Stores/notification'
const notifications = useNotificationStore()
</script>

<template>
  <div class="fixed top-5 right-5 space-y-2 z-50">
    <div
        v-for="(n, i) in notifications.notifications"
        :key="i"
        class="bg-white border rounded p-4 shadow text-sm"
    >
      {{ n.message }}
    </div>
  </div>
</template>
