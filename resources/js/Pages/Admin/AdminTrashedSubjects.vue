<script setup>

import SubjectsTrashedList from "@/components/Admin/Subjects/SubjectsTrashedList.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
const props = defineProps({
    subjects: Object,
});
</script>

<template>
    <AdminLayout>
        <SubjectsTrashedList
            :subjects="subjects"
        />
    </AdminLayout>
</template>

<style scoped>

</style>
