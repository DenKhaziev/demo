<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import TicketMessages from "@/components/Parent/TicketMessages.vue";

const props = defineProps({
    ticket: Object,
    messages: Array,
});
</script>

<template>
    <MainLayout>
        <AuthenticatedLayout>
            <TicketMessages :ticket="ticket" :messages="messages"/>
        </AuthenticatedLayout>
    </MainLayout>
</template>

<style scoped>

</style>
