<script setup>
import {defineProps} from "vue";
import CreateSubject from "@/components/Admin/Subjects/CreateSubject.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    subjects: Array,
});
</script>

<template>
    <AdminLayout>
        <CreateSubject v-bind="{subjects}"/>
    </AdminLayout>
</template>

<style scoped>

</style>
