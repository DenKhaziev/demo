<script setup>

import AdminLayout from "@/Layouts/AdminLayout.vue";
import MessagesList from "@/components/Admin/Tickets/MessagesList.vue";

import {defineProps} from "vue";
const props = defineProps({
    ticket: Object,
    messages: Array,
    currentUserId: Number
});
</script>

<template>
    <AdminLayout>
        <MessagesList v-bind="{ticket, messages}"/>
    </AdminLayout>
</template>

<style scoped>

</style>
