<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
})
</script>

<template>
    <button
        class="px-6 py-2 rounded-2xl text-white font-semibold bg-gradient-to-r from-[#800020] to-[#a3243a] shadow-md transition-all duration-300 hover:brightness-110 hover:scale-[1.02] focus:outline-none"
        :type="type"
    >
        <slot />
    </button>
</template>


