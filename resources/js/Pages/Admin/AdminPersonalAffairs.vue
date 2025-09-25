<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import RequestList from "@/components/Admin/Requests/RequestList.vue";

const props = defineProps({
    personalAffair: Object
})
</script>

<template>
    <AdminLayout>
        <RequestList
            title="Формирование личных дел"
            :items="personalAffair"
            :getUserName="item => item.child.user.name"
            :getChildName="item => item.child.name"
            :getGradeNumber="item => item.grade_id"
            :parentLink="item => route('parents.children', item.child.user.id)"
            :childLink="item => route('children.show', item.child.id)"
            :doneRoute="id => route('personal.affairs.update', id)"
            :description="() => 'запросил формирование личного дела для'"
            done-label="Сформировано"
        />
    </AdminLayout>
</template>

<style scoped>

</style>
