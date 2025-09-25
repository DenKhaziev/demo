<script setup>

import TestShowHeader from "@/components/Admin/Tests/TestShowHeader.vue";
import Card from "@/components/Admin/Layout/Card.vue";
import CardTable from "@/components/Admin/Layout/CardTable.vue";
import TestQuestionRow from "@/components/Admin/Tests/TestQuestionRow.vue";
import DeleteButton from "@/components/ui/DeleteButton.vue";
import CardTableQuestionHead from "@/components/Admin/Tests/CardTableQuestionHead.vue";
import CardHeader from "@/components/Admin/Layout/CardHeader.vue";
import GoBackbutton from "@/components/ui/GoBackbutton.vue";
import BlueButton from "@/components/ui/BlueButton.vue";
import CreateTestQuestionForm from "@/components/Admin/Tests/CreateTestQuestionForm.vue";
import {useForm} from "@inertiajs/vue3";
import {ref} from "vue";
import SlideToggle from "@/components/ui/SlideToggle.vue";

const props = defineProps({
    questions: Array,
    test: Array,
});
const isOpen = ref(false);

const toggle = () => {
    isOpen.value = !isOpen.value;
};

const deleteQuestion = (question) => {
    const formDelete = useForm({
        question: question.id,
    });
    if (confirm("После удаления вопроса его можно будет восстановить в блоке 'Удаленные вопросы' ниже")) {
        formDelete.delete(route('questions.destroy', question.id));
    }
};
</script>

<template>
    <Card>
    <GoBackbutton :fallback-url="route('tests')" />
        <CardHeader>Список вопросов к тесту</CardHeader>
        <TestShowHeader :test="test"/>
        <div @click="toggle" class="flex items-center cursor-pointer mb-2">
            <BlueButton
                :isOpen="isOpen"
                class="mt-3"
            >
                {{isOpen ? 'Скрыть форму' : 'Добавить новый вопрос к тестированию' }}
            </BlueButton>
        </div>
        <SlideToggle :isOpen="isOpen">
            <CreateTestQuestionForm :test="test"/>
        </SlideToggle>
        <CardTable>
            <CardTableQuestionHead/>
            <TestQuestionRow :questions="questions">
                <template v-slot="{ question }">
                    <td class="border px-4 py-2 text-center"  @click.stop>
                             <DeleteButton @click="()=>deleteQuestion(question)"/>
                    </td>
                </template>
            </TestQuestionRow>
        </CardTable>


    </Card>
</template>

<style scoped>

</style>
