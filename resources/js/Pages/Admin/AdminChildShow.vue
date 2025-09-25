<script setup>
import ChildShow from "@/components/Admin/Children/ChildShow.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
const props = defineProps({
    passedTests: Array,
    child: Object,
    remainingTests: Array
})
</script>

<template>
    <AdminLayout>
        <ChildShow v-bind="{ passedTests, remainingTests, child}"/>
    </AdminLayout>
</template>

<style scoped>

</style>
