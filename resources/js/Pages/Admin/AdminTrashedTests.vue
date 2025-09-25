<script setup>

import AdminLayout from "@/Layouts/AdminLayout.vue";
import TestsTrashedList from "@/components/Admin/Tests/TestsTrashedList.vue";
const props = defineProps({
    tests: Object,
});
</script>

<template>
    <AdminLayout>
        <TestsTrashedList :tests="tests"/>
    </AdminLayout>
</template>

<style scoped>

</style>
