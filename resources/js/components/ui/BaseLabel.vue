<script setup>
const props = defineProps({
    forInput: String,
    text: String
})
</script>

<template>
    <label for="forInput" class="block text-gray-700 font-bold mb-2"> {{ text }}</label>
</template>

<style scoped>

</style>
