
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
        class="px-6 py-2 rounded-2xl border-2 border-[#800020] text-[#800020] font-semibold bg-transparent hover:bg-[#800020] hover:text-white transition-all duration-300 shadow-sm hover:shadow-md focus:outline-none"
        :type="type"
    >
      <slot />
    </button>


</template>

<style scoped>

</style>
