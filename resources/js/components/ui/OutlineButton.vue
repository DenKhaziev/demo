<script setup>
import {defineProps} from "vue";

const props = defineProps({
    onClick: Function,
    arg: Object,
});
const handleEdit = () => {
    if (props.onClick) {
        props.onClick(props.arg);
    }
};
</script>

<template>
    <button
        @click="handleEdit"
        class="px-4 py-2 rounded-lg border border-transparent bg-gradient-to-r from-purple-500 to-indigo-500 text-white font-semibold shadow-md hover:shadow-lg hover:-translate-y-1 transition-transform duration-300 ease-in-out hover:from-purple-600 hover:to-indigo-600 xl:text-sm "
    >
        <slot />
    </button>
</template>

<style scoped>

</style>
