<script setup>

import TestShowHeader from "@/components/Admin/Tests/TestShowHeader.vue";
import Card from "@/components/Admin/Layout/Card.vue";
import CardTable from "@/components/Admin/Layout/CardTable.vue";
import TestQuestionRow from "@/components/Admin/Tests/TestQuestionRow.vue";
import DeleteButton from "@/components/ui/DeleteButton.vue";
import {router, useForm} from "@inertiajs/vue3";
import CardTableQuestionHead from "@/components/Admin/Tests/CardTableQuestionHead.vue";
import BlueButton from "@/components/ui/BlueButton.vue";
import CardHeader from "@/components/Admin/Layout/CardHeader.vue";
import GoBackbutton from "@/components/ui/GoBackbutton.vue";

const props = defineProps({
    questions: Array,
    test: Array,
});
const restoreQuestion = (question) => {
    router.put(route('questions.restore', question.id), {}, {
        onSuccess:() => {
            question.deleted_at = null;
        }
    })
};
</script>

<template>
    <GoBackbutton :fallback-url="route('tests.show', test.id)" />
    <Card>
        <CardHeader class="text-red-700">Удаленные вопросы </CardHeader>
        <TestShowHeader :test="test"/>
        <CardTable>
            <CardTableQuestionHead/>
            <TestQuestionRow :questions="questions">
                <template v-slot="{ question }">
                    <td class="border px-4 py-2 text-center"  @click.stop>
                        <BlueButton
                            :onClick="() => restoreQuestion(question)"
                        >
                            Восстановить
                        </BlueButton>
                    </td>
                </template>
            </TestQuestionRow>
        </CardTable>
    </Card>
</template>

<style scoped>

</style>
