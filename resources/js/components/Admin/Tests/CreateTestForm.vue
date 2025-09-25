<script setup>
import { useTestFormStore } from '@/Stores/testFormStore';
import BaseSelect from '@/components/ui/BaseSelect.vue';
import SaveButton from '@/components/ui/SaveButton.vue';
import Card from '@/components/Admin/Layout/Card.vue';
import CardHeader from '@/components/Admin/Layout/CardHeader.vue';
import {onMounted} from "vue";
import CreateTestQuestionBody from "@/components/Admin/Tests/CreateTestQuestionBody.vue";

const store = useTestFormStore();
const props = defineProps({
    testTypes: Array,
    subjects: Array,
    grades: Array
})

onMounted(() => {
    store.setTestTypes(props.testTypes)
    store.setSubjects(props.subjects)
    store.setGrades(props.grades)
})
</script>

<template>
    <form @submit.prevent="store.submitTest" class="w-2/3">
        <Card>
            <CardHeader>Добавить новое тестирование</CardHeader>

            <BaseSelect
                label="Тип теста:"
                v-model="store.form.test_type_id"
                :options="store.testTypes"
                optionLabel="type"
                optionValue="id"
            />

            <BaseSelect
                label="Предмет:"
                v-model="store.form.subject_id"
                :options="store.subjects"
                optionLabel="subject"
                optionValue="id"
            />

            <BaseSelect
                label="Класс:"
                v-model="store.form.grade_id"
                :options="store.grades"
                optionLabel="class_number"
                optionValue="id"
            />

            <CreateTestQuestionBody/>
        </Card>
        <SaveButton type="submit" class="mt-4 p-3">Сохранить тест</SaveButton>
    </form>
</template>

