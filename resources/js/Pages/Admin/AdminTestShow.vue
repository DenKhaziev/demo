<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import TestShow from "@/components/Admin/Tests/TestShow.vue";
import GoBackbutton from "@/components/ui/GoBackbutton.vue";
import SoftDeletedItemsButton from "@/components/ui/SoftDeletedItemsButton.vue";
import BlueButton from "@/components/ui/BlueButton.vue";

const props = defineProps({
    questions: Array,
    test: Array,
});

</script>

<template>
    <AdminLayout>
        <TestShow v-bind="{questions, test}"/>
        <SoftDeletedItemsButton
            :href="route('questions.trashed', test.id)"
            label="Перейти к удаленным вопросам данного теста"/>
    </AdminLayout>
</template>

<style scoped>

</style>
