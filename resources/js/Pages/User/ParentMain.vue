<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ParentIndex from "@/components/Parent/ParentIndex.vue";
import MainLayout from "@/Layouts/MainLayout.vue";

const props = defineProps({
    parent: Object,
    children: Array,
})
</script>

<template>
    <MainLayout>
        <AuthenticatedLayout>
            <div class="w-full px-3 sm:w-5/6 mx-auto md:mt-20 mt-5">
            <ParentIndex :parent="parent" :children="children"/>
            </div>
        </AuthenticatedLayout>
    </MainLayout>

</template>

<style scoped>

</style>
