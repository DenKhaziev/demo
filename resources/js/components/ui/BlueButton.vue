<script setup>
const emit = defineEmits(['click']);
</script>

<template>

    <button
        @click="$emit('click')"
        class="bg-blue-500 text-white rounded hover:bg-blue-700 p-2 shadow-xl font-semibold xl:text-sm"
    >
        <slot />
    </button>
</template>

<style scoped>

</style>
