<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import RequestList from "@/components/Admin/Requests/RequestList.vue";

const props = defineProps({
    accessToAccount: Object
})
</script>

<template>
    <AdminLayout>
        <RequestList
            title="Запросы на активацию личного кабинета ученика"
            :items="accessToAccount"
            :getUserName="item => item.child.user.name"
            :getChildName="item => item.child.name"
            :getGradeNumber="item => item.grade_id"
            :parentLink="item => route('parents.children', item.child.user.id)"
            :childLink="item => route('children.show', item.child.id)"
            :doneRoute="id => route('activate.access.update', id)"
            :deleteRoute="id => route('activate.access.delete', id)"
            :description="() => 'запросил(а) активацию личного кабинета'"
            done-label="Активировать"
            delete-label="В архив"
            un-active-label="Доработка"
        />
    </AdminLayout>
</template>

<style scoped>

</style>
