<script setup>
import { onMounted, ref } from 'vue';
import { router } from '@inertiajs/vue3';

const isLoading = ref(false);

onMounted(() => {
    router.on('start', () => isLoading.value = true);
    router.on('finish', () => isLoading.value = false);
});
</script>

<template>
  <transition name="fade">
    <div
      v-if="isLoading"
      class="fixed inset-0 bg-black/30 backdrop-blur-sm z-[9999] flex items-center justify-center"
    >
      <div class="animate-spin rounded-full h-16 w-16 border-4 border-blue-500 border-t-transparent"></div>
    </div>
  </transition>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>