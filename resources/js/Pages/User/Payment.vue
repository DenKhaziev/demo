<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import AboutPayment from "@/components/Parent/AboutPayment.vue";
const props = defineProps({
    prices: Object,
})
</script>

<template>
    <MainLayout>
        <AuthenticatedLayout>
            <AboutPayment  :prices="prices"/>
        </AuthenticatedLayout>
    </MainLayout>

</template>

<style scoped>

</style>
