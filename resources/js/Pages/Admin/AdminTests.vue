<script setup>
import TestsList from "@/components/Admin/Tests/TestsList.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import SoftDeletedItemsButton from "@/components/ui/SoftDeletedItemsButton.vue";

const props = defineProps({
    tests: Array
});
</script>

<template>
    <AdminLayout>
        <TestsList v-bind="{tests}"/>
        <SoftDeletedItemsButton
            :href="route('tests.trashed')"
            label="Перейти к удаленным предметам"
        />
    </AdminLayout>
</template>

<style scoped>

</style>

