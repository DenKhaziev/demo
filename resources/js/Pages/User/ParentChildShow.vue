<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import ParentChild from "@/components/Parent/ParentChild.vue";

const props = defineProps({
    child: Object,
    passedTests: Array,
    remainingTests: Array,
})
</script>

<template>
    <MainLayout>
        <authenticated-layout>
            <div class="w-full px-2 mx-auto mt-5 sm:w-5/6 sm:mt-20">
                <ParentChild :child="child" :passedTests="passedTests" :remainingTests="remainingTests"/>
            </div>
        </authenticated-layout>
    </MainLayout>
</template>

<style scoped>

</style>
