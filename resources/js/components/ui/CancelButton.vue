<script setup>
// import {defineProps} from "vue";
//
// const props = defineProps({
//     onClick: Function,
//     arg: Object,
// });
// const handleEdit = () => {
//     if (props.onClick) {
//         props.onClick(props.arg);
//     }
// };
const emit = defineEmits(['click']);
</script>

<template>
    <button
        @click="$emit('click')"
        class=" text-red-500 p-2 border border-red-500 rounded hover:bg-red-500 hover:text-white shadow-xl font-semibold xl:text-sm "
    >
        <slot/>
    </button>
</template>

<style scoped>

</style>
