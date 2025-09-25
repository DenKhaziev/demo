<script setup>
import FileUploaderButton from "@/components/ui/FileUploaderButton.vue";
defineProps({
    child: Array
})

</script>

<template>
    
    <FileUploaderButton
        :child="child"
        :child-id="child.id"
        :grade-id="child.grade_id"
        field="document_birth"
        label="Свидетельство о рождении"
        sub-label="не паспорт ученика"
    />
        
    
    <FileUploaderButton
        :child="child"
        :child-id="child.id"
        :grade-id="child.grade_id"
        field="document_application_for_transfer"
        label="Заявление на зачисление"
    />
    <FileUploaderButton
        :child="child"
        :child-id="child.id"
        :grade-id="child.grade_id"
        field="document_personal_of_processing_data"
        label="Согласие на ОПД"

    />
    <FileUploaderButton
        :child="child"
        :child-id="child.id"
        :grade-id="child.grade_id"
        field="document_payment"
        label="Чек об оплате"
        sub-label="чек должен быть в статусе 'оплачено' или 'успешно'"
    />

    <FileUploaderButton
        :child="child"
        v-if="child.grade_id > 9"
        :child-id="child.id"
        :grade-id="child.grade_id"
        field="document_from_9_graduate"
        label="Аттестат за 9 классов"

    />

</template>

<style scoped>

</style>
