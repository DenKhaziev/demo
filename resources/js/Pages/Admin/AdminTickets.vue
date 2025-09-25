<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import TicketsList from "@/components/Admin/Tickets/TicketsList.vue";
import {defineProps} from "vue";
const props = defineProps({
    tickets: Array
});
</script>

<template>
    <AdminLayout>
        <TicketsList v-bind="{tickets}"/>
    </AdminLayout>
</template>

<style scoped>

</style>
