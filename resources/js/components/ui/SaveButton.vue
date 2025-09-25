<script setup>
// import { defineProps } from 'vue';
//
// const props = defineProps({
//     onUpdate: Function,
//     arg: Object,
//
// });
//
// const handleClick = () => {
//     if (props.onUpdate) {
//         props.onUpdate(props.arg);
//     }
// };

const emit = defineEmits(['click']);

</script>=>

<template>
    <button
        @click="$emit('click')"
        class="bg-green-500 text-white p-2 rounded hover:bg-green-700 shadow-xl font-semibold xl:text-sm "
    >
        <slot/>
    </button>
</template>

<style scoped>

</style>
