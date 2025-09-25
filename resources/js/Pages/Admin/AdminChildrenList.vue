<script setup>
import ChildrenList from "@/components/Admin/Children/ChildrenList.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {defineProps, ref} from "vue";
import ParentsList from "@/components/Admin/Parents/ParentsList.vue";

const props = defineProps({
    children: Array,
    search: String,
    status: String,
    grade: Number

});

const gradeId = ref(props.grade ?? null);
const changeGrade = (id) => {
    gradeId.value = id;
};
</script>

<template>
    <AdminLayout>
        <ChildrenList :children="children" :search="search" :status="status" :changeGrade="changeGrade" :gradeId="gradeId" />
    </AdminLayout>
</template>

<style scoped>

</style>
