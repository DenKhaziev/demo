<script setup>
const props = defineProps({
    isActive: {
        type: Boolean,
        default: false
    },
    onClick: {
        type: Function,
        required: true
    }
})
</script>

<template>
    <button
        @click="onClick"
        :class="[
      'px-4 py-2 rounded hover:bg-gray-300 transition',
      isActive ? 'bg-blue-500 text-white border border-black' : 'bg-gray-200 text-black'
    ]"
    >
        <slot />
    </button>
</template>

