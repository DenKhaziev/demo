<script setup>
import {computed} from "vue";
const props = defineProps({
    tests: Array,
    sortedTests: Function
})

const groupedTests = computed(() => {
    const groups = {};
    if (!props.tests) return groups;
    props.tests.forEach(test => {
        if (!groups[test.grade_id]) {
            groups[test.grade_id] = [];
        }
        groups[test.grade_id].push(test);
    });
    return groups;
});
</script>

<template>
    <div v-for="(tests, grade) in groupedTests" :key="grade" class="mb-8">
        <h2 class="text-2xl font-semibold mb-2 text-gray-700">{{ grade }} класс</h2>
        <slot name="table" :tests="tests" :sortedTests="sortedTests" />
    </div>
</template>

<style scoped>

</style>
