<script setup>

import {router, useForm} from "@inertiajs/vue3";

const props = defineProps({
    tests: Array,
    sortedTests: Function,
})


</script>

<template>
    <tbody>
        <tr v-for="test in sortedTests(tests)" :key="test.id"
            @click="() => router.visit(route('tests.show', test.id))"
            class="cursor-pointer hover:bg-gray-200"
        >
            <slot name="test" :test="test"/>
            <slot name="actions" :test="test"/>
        </tr>
    </tbody>
</template>

<style scoped>

</style>
