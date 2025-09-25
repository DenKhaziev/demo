<script setup>
import ParentsChildren from "@/components/Admin/Children/ParentsChildren.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    parent: Object,
    children: Array,
});

</script>
<template>
    <AdminLayout>
        <ParentsChildren v-bind="{ parent, children }"/>
    </AdminLayout>
</template>

<style scoped>

</style>
