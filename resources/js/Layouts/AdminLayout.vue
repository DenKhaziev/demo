<script setup>
import AdminSidebar from "@/components/Admin/Layout/AdminSidebar.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NotificationModal from "@/components/Modal/NotificationModal.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
</script>

<template>
    <MainLayout>

    <AuthenticatedLayout>

    <div class="flex w-5/6 mx-auto justify-between mt-20">
        <AdminSidebar />
        <main class="flex-1 pl-20 mb-10">
            <slot />
        </main>
    </div>
    </AuthenticatedLayout>
    </MainLayout>
</template>


