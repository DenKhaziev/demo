<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import SubjectsList from "@/components/Admin/Subjects/SubjectsList.vue";
import {Link} from "@inertiajs/vue3";
import SoftDeletedItemsButton from "@/components/ui/SoftDeletedItemsButton.vue";
const props = defineProps({
    subjects: Object,
});

</script>

<template>
    <AdminLayout>
        <SubjectsList v-bind="{subjects}"/>
        <SoftDeletedItemsButton
            :href="route('subjects.trashed')"
            label="Перейти к удаленным предметам"
        />
    </AdminLayout>
</template>

<style scoped>

</style>
