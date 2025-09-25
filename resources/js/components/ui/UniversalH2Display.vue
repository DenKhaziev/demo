<script setup>
import { defineProps } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    label: String,
    value: [String, Number],
    isLink: Boolean,
    linkRoute: String,
});
</script>

<template>
    <div class="flex flex-col items-center justify-center h-full text-center">
        <h2 class="text-sm text-gray-500 mb-1">{{ label }}</h2>
        <span class="text-red-900 font-bold text-lg">
      <Link v-if="isLink" :href="linkRoute" class="hover:underline">{{ value }}</Link>
      <span v-else>{{ value }}</span>
    </span>
    </div>
</template>
