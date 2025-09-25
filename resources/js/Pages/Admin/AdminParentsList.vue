<script setup>
import {computed, defineProps, ref} from "vue";
import ParentsList from "@/components/Admin/Parents/ParentsList.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

import { router } from "@inertiajs/vue3";

const props = defineProps({
    parents: Object,
    search: String,
    status: String
});

const status = ref(props.status ?? 'active'); // default active

const currentStatus = computed(() => props.status ?? 'active');

const changeStatus = (newStatus) => {
    router.get(route('parents.index'), { status: newStatus, search: props.search }, { preserveState: true, preserveScroll: true });
};
</script>

<template>
    <AdminLayout>
        <ParentsList :parents="parents" :search="search" :status="status" />
    </AdminLayout>
</template>
