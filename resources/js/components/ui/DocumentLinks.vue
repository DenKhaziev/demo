<script setup>
const props = defineProps({
    doc: {
        type: Object,
        required: true
    },
    child: Object,

});

</script>

<template>
    <div v-if="child.grade_id === doc.grade_id">
        <ul class="space-y-1">
            <li v-if="doc.document_birth">
                <a :href="`/storage/documents/${child.id}/${doc.grade_id}/${doc.document_birth}`" target="_blank" class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-xs p-1 font-semibold rounded shadow-2xl">
                    Свидетельство о рождении
                </a>
            </li>
            <li v-if="doc.document_application_for_transfer">
                <a :href="`/storage/documents/${child.id}/${doc.grade_id}/${doc.document_application_for_transfer}`" target="_blank" class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-xs p-1 font-semibold rounded shadow-2xl">
                    Заявление на зачисление
                </a>
            </li>
            <li v-if="doc.document_personal_of_processing_data">
                <a :href="`/storage/documents/${child.id}/${doc.grade_id}/${doc.document_personal_of_processing_data}`" target="_blank" class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-xs p-1 font-semibold rounded shadow-2xl">
                    Согласие на обработку данных
                </a>
            </li>
            <li v-if="doc.document_payment">
                <a :href="`/storage/documents/${child.id}/${doc.grade_id}/${doc.document_payment}`" target="_blank" class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-xs p-1 font-semibold rounded shadow-2xl">
                    Чек об оплате
                </a>
            </li>
            <li v-if="doc.document_from_9_graduate">
                <a :href="`/storage/documents/${child.id}/${doc.grade_id}/${doc.document_from_9_graduate}`" target="_blank" class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-xs p-1 font-semibold rounded shadow-2xl">
                    Аттестат за 9 классов
                </a>
            </li>
        </ul>
    </div>
</template>
<style scoped>

</style>
