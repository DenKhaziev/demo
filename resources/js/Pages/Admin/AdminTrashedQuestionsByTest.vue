<script setup>

import AdminLayout from "@/Layouts/AdminLayout.vue";
import TestShowTrashed from "@/components/Admin/Tests/TestShowTrashed.vue";
const props = defineProps({
    questions: Array,
    test: Array
})
</script>

<template>
    <AdminLayout>
        <TestShowTrashed :questions="questions" :test="test"/>
    </AdminLayout>
</template>

<style scoped>

</style>
