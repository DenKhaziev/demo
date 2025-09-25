<script setup>

import AdminLayout from "@/Layouts/AdminLayout.vue";
import RequestList from "@/components/Admin/Requests/RequestList.vue";
const props = defineProps({
    certificates: Object
})
</script>

<template>
    <AdminLayout>
        <RequestList
            title="Запросы на получение справки об окончании учебного года"
            :items="certificates"
            :getUserName="item => item.child.user.name"
            :getChildName="item => item.child.name"
            :getGradeNumber="item => item.grade_id"
            :parentLink="item => route('parents.children', item.child.user.id)"
            :childLink="item => route('children.show', item.child.id)"
            :doneRoute="id => route('cert_requests.update', id)"
            :description="() => 'запросил справку для'"
        />
    </AdminLayout>
</template>

<style scoped>

</style>
