<script setup>
import {ref} from "vue";

const props = defineProps({
    gradeId: Number,
    changeGrade: Function,
    // changeStyle: Function
})


</script>

<template>
    <div class="mb-4 flex flex-wrap gap-2">
        <button
            @click="() => props.changeGrade(null)"
            :class="[ 'px-4 py-2 rounded', props.gradeId === null ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300 ' ]"
        >
            Все классы
        </button>
        <button
            v-for="i in 11"
            :key="i"
            @click="() => props.changeGrade(i)"
            :class="[ 'px-4 py-2 rounded', props.gradeId === i ? 'bg-blue-500 text-white xl:text-sm ' : 'bg-gray-200 hover:bg-gray-300 xl:text-sm ' ]"
        >
            {{ i }}
        </button>
    </div>
</template>

<style scoped>

</style>
